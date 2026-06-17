<?php
/**
 * Security Helper Functions
 * CSRF Protection, XSS Prevention, Input Validation
 * For PHP 8.5.7
 */

!function_exists('readover') && exit('Forbidden');

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token($token) {
    if(!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }

    // Token expires after 2 hours
    if(time() - $_SESSION['csrf_token_time'] > 7200) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }

    // Timing-safe comparison
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF Token HTML Input
 */
function csrf_token_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Enhanced XSS Protection
 */
function xss_clean($data) {
    if(is_array($data)) {
        return array_map('xss_clean', $data);
    }

    // Remove null bytes
    $data = str_replace(chr(0), '', $data);

    // Fix broken HTML entities
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);

    // Remove javascript: and data: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*d[\x00-\x20]*a[\x00-\x20]*t[\x00-\x20]*a[\x00-\x20]*:#iu', '$1=$2nodata...', $data);

    // Remove event handlers
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove script and style tags
    $data = preg_replace('#</*(?:script|style|iframe|embed|object)[^>]*+>#i', '', $data);

    return $data;
}

/**
 * Safe HTML Output
 */
function html_escape($text) {
    return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Validate Email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Username
 * Allow: letters, numbers, underscore, dash (no spaces, no special chars)
 */
function validate_username($username, $min_length = 3, $max_length = 20) {
    if(strlen($username) < $min_length || strlen($username) > $max_length) {
        return false;
    }
    return preg_match('/^[a-zA-Z0-9_-]+$/', $username) === 1;
}

/**
 * Secure Password Hash (replaces MD5)
 */
function secure_password_hash($password) {
    return password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 2
    ]);
}

/**
 * Verify Password
 */
function secure_password_verify($password, $hash) {
    // Support legacy MD5 hashes
    if(strlen($hash) == 32 && ctype_xdigit($hash)) {
        // Legacy MD5 - needs migration
        return md5($password) === $hash;
    }

    return password_verify($password, $hash);
}

/**
 * Check if password needs rehash
 */
function password_needs_rehash_check($hash) {
    // Legacy MD5
    if(strlen($hash) == 32 && ctype_xdigit($hash)) {
        return true;
    }

    return password_needs_rehash($hash, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 2
    ]);
}

/**
 * Rate Limiting
 */
function check_rate_limit($action, $max_attempts = 5, $time_window = 300) {
    global $onlineip;

    $cache_file = "session/ratelimit_{$action}_" . md5($onlineip) . ".txt";

    if(file_exists($cache_file)) {
        $data = @file_get_contents($cache_file);
        list($attempts, $first_attempt) = explode('|', $data);

        if(time() - $first_attempt > $time_window) {
            // Reset counter
            file_put_contents($cache_file, "1|" . time());
            return true;
        }

        if($attempts >= $max_attempts) {
            return false;
        }

        // Increment counter
        file_put_contents($cache_file, ($attempts + 1) . "|" . $first_attempt);
        return true;
    } else {
        // First attempt
        file_put_contents($cache_file, "1|" . time());
        return true;
    }
}

/**
 * Sanitize filename for upload
 */
function sanitize_filename($filename) {
    // Remove path information
    $filename = basename($filename);

    // Remove dangerous characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

    // Prevent double extensions
    $filename = preg_replace('/\.+/', '.', $filename);

    // Add timestamp to prevent overwrites
    $parts = pathinfo($filename);
    $filename = $parts['filename'] . '_' . time() . '.' . $parts['extension'];

    return $filename;
}

/**
 * Validate file upload
 */
function validate_file_upload($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 2097152) {
    $errors = [];

    if(!isset($file['error']) || is_array($file['error'])) {
        $errors[] = '无效的上传参数';
        return $errors;
    }

    switch($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errors[] = '文件大小超过限制';
            return $errors;
        case UPLOAD_ERR_NO_FILE:
            $errors[] = '没有文件被上传';
            return $errors;
        default:
            $errors[] = '未知上传错误';
            return $errors;
    }

    if($file['size'] > $max_size) {
        $errors[] = '文件大小超过 ' . ($max_size / 1024 / 1024) . 'MB';
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed_types)) {
        $errors[] = '不允许的文件类型: ' . $ext;
    }

    // Verify MIME type matches extension
    $mime_map = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];

    if(isset($mime_map[$ext]) && $mime !== $mime_map[$ext]) {
        $errors[] = '文件类型与扩展名不匹配';
    }

    return $errors;
}

/**
 * Secure Redirect
 * Prevent open redirect vulnerability
 */
function safe_redirect($url, $allowed_domains = []) {
    global $db_bbsurl;

    // Parse URL
    $parsed = parse_url($url);

    // Relative URL is safe
    if(!isset($parsed['host'])) {
        header("Location: $url");
        exit;
    }

    // Check if domain is in whitelist
    $allowed_domains[] = parse_url($db_bbsurl, PHP_URL_HOST);

    if(in_array($parsed['host'], $allowed_domains)) {
        header("Location: $url");
        exit;
    }

    // Unsafe redirect attempt
    header("Location: index.php");
    exit;
}

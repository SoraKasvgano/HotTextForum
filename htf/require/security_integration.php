/**
 * Security Integration Patch for HotTextForum
 * Apply CSRF Protection and Session Security
 *
 * USAGE:
 * Include this file after global.php in pages that need protection
 */

// 1. Load security.php if not already loaded
if(!function_exists('generate_csrf_token')) {
    require_once __DIR__ . '/require/security.php';
}

// 2. CSRF Protection for POST forms
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Skip CSRF check for specific actions if needed
    $csrf_skip_actions = ['api_no_csrf']; // Add actions that don't need CSRF

    if(!in_array($action ?? '', $csrf_skip_actions)) {
        if(empty($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            showmsg('安全验证失败，请刷新页面重新提交！');
        }
    }
}

// 3. Rate Limiting Helper
function apply_rate_limit($action_name, $max_attempts = 5, $time_window = 300) {
    if(function_exists('check_rate_limit')) {
        if(!check_rate_limit($action_name, $max_attempts, $time_window)) {
            $minutes = ceil($time_window / 60);
            showmsg("操作过于频繁，请{$minutes}分钟后再试！");
        }
    }
}

// 4. Session Security - Regenerate ID on privilege change
function secure_session_regenerate() {
    if(session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

// 5. Secure Logout - Destroy session completely
function secure_logout() {
    if(session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }
}

// 6. XSS Protection Wrapper
function safe_output($text, $allow_html = false) {
    if($allow_html) {
        // For BBCode/HTML content, only escape dangerous tags
        return xss_clean($text);
    } else {
        // For plain text, full escape
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

// 7. Input Validation Helpers
function validate_input($data, $type = 'string', $options = []) {
    switch($type) {
        case 'email':
            return validate_email($data);

        case 'username':
            $min = $options['min'] ?? 3;
            $max = $options['max'] ?? 20;
            return validate_username($data, $min, $max);

        case 'required':
            return !empty(trim($data));

        case 'integer':
            return filter_var($data, FILTER_VALIDATE_INT) !== false;

        case 'url':
            return filter_var($data, FILTER_VALIDATE_URL) !== false;

        default:
            return true;
    }
}

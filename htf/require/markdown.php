<?php
/**
 * Markdown Parser for HotTextForum
 * Lightweight PHP Markdown implementation
 * Compatible with PHP 8.5.7
 * Security: XSS-safe output
 */

!function_exists('readover') && exit('Forbidden');

/**
 * Convert Markdown to HTML
 * @param string $text Markdown text
 * @param bool $safe Enable XSS filtering (default: true)
 * @return string HTML output
 */
function markdown_to_html($text, $safe = true) {
    if (empty($text)) return '';

    // Security: Escape HTML entities first
    if ($safe) {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    // Store code blocks to prevent parsing inside them
    $code_blocks = [];
    $text = preg_replace_callback('/```(\w*)\n(.*?)\n```/s', function($matches) use (&$code_blocks) {
        $lang = $matches[1];
        $code = $matches[2];
        $key = '___CODE_BLOCK_' . count($code_blocks) . '___';
        $code_blocks[$key] = '<pre><code class="language-' . htmlspecialchars($lang) . '">' . $code . '</code></pre>';
        return $key;
    }, $text);

    // Store inline code to prevent parsing inside them
    $inline_codes = [];
    $text = preg_replace_callback('/`([^`]+)`/', function($matches) use (&$inline_codes) {
        $key = '___INLINE_CODE_' . count($inline_codes) . '___';
        $inline_codes[$key] = '<code>' . $matches[1] . '</code>';
        return $key;
    }, $text);

    // Headers (### -> h3, ## -> h2, # -> h1)
    $text = preg_replace('/^######\s+(.+)$/m', '<h6>$1</h6>', $text);
    $text = preg_replace('/^#####\s+(.+)$/m', '<h5>$1</h5>', $text);
    $text = preg_replace('/^####\s+(.+)$/m', '<h4>$1</h4>', $text);
    $text = preg_replace('/^###\s+(.+)$/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^##\s+(.+)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^#\s+(.+)$/m', '<h1>$1</h1>', $text);

    // Bold: **text** or __text__
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);

    // Italic: *text* or _text_
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/_(.+?)_/', '<em>$1</em>', $text);

    // Strikethrough: ~~text~~
    $text = preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);

    // Links: [text](url)
    $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2" target="_blank" rel="noopener">$1</a>', $text);

    // Images: ![alt](url)
    $text = preg_replace('/!\[([^\]]*)\]\(([^\)]+)\)/', '<img src="$2" alt="$1" class="markdown-img">', $text);

    // Unordered lists: - item or * item
    $text = preg_replace_callback('/^[\*\-]\s+(.+)$/m', function($matches) {
        static $in_list = false;
        $item = '<li>' . $matches[1] . '</li>';
        if (!$in_list) {
            $in_list = true;
            return '<ul>' . $item;
        }
        return $item;
    }, $text);
    $text = preg_replace('/<\/li>\n(?!<li>)/', '</li></ul>', $text);

    // Ordered lists: 1. item
    $text = preg_replace_callback('/^\d+\.\s+(.+)$/m', function($matches) {
        static $in_list = false;
        $item = '<li>' . $matches[1] . '</li>';
        if (!$in_list) {
            $in_list = true;
            return '<ol>' . $item;
        }
        return $item;
    }, $text);
    $text = preg_replace('/<\/li>\n(?!<li>)/', '</li></ol>', $text);

    // Blockquotes: > text
    $text = preg_replace('/^>\s+(.+)$/m', '<blockquote>$1</blockquote>', $text);
    $text = preg_replace('/<\/blockquote>\n<blockquote>/', "\n", $text);

    // Horizontal rule: --- or ***
    $text = preg_replace('/^[\-\*]{3,}$/m', '<hr>', $text);

    // Line breaks: double newline = paragraph
    $text = preg_replace('/\n\n+/', '</p><p>', $text);
    $text = '<p>' . $text . '</p>';

    // Single line break
    $text = preg_replace('/\n/', '<br>', $text);

    // Clean up empty paragraphs
    $text = preg_replace('/<p><\/p>/', '', $text);
    $text = preg_replace('/<p>\s*<\/p>/', '', $text);

    // Restore code blocks
    foreach ($code_blocks as $key => $code) {
        $text = str_replace($key, $code, $text);
    }

    // Restore inline code
    foreach ($inline_codes as $key => $code) {
        $text = str_replace($key, $code, $text);
    }

    return $text;
}

/**
 * Detect if text contains Markdown syntax
 * @param string $text
 * @return bool
 */
function is_markdown($text) {
    // Check for common Markdown patterns
    $patterns = [
        '/^#{1,6}\s/',           // Headers
        '/\*\*.*?\*\*/',         // Bold
        '/\[.*?\]\(.*?\)/',      // Links
        '/```/',                  // Code blocks
        '/^[\*\-]\s/m',          // Lists
        '/^>\s/m'                // Blockquotes
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $text)) {
            return true;
        }
    }

    return false;
}

/**
 * Convert content with auto-detection
 * Supports both BBCode and Markdown
 * @param string $message
 * @param array $allow BBCode settings
 * @param string $type
 * @return string
 */
function convert_with_markdown($message, $allow, $type = "post") {
    // Check if Markdown mode is enabled
    if (is_markdown($message)) {
        return markdown_to_html($message);
    }

    // Fallback to BBCode
    if (function_exists('convert')) {
        return convert($message, $allow, $type);
    }

    return htmlspecialchars($message);
}

/**
 * Get Markdown help text for editor
 * @return string
 */
function get_markdown_help() {
    return <<<HTML
<div class="markdown-help" style="display:none;">
    <h4>Markdown 语法帮助</h4>
    <table class="markdown-help-table">
        <tr>
            <th>语法</th>
            <th>效果</th>
        </tr>
        <tr>
            <td># 标题</td>
            <td><h1>一级标题</h1></td>
        </tr>
        <tr>
            <td>## 标题</td>
            <td><h2>二级标题</h2></td>
        </tr>
        <tr>
            <td>**粗体**</td>
            <td><strong>粗体</strong></td>
        </tr>
        <tr>
            <td>*斜体*</td>
            <td><em>斜体</em></td>
        </tr>
        <tr>
            <td>~~删除线~~</td>
            <td><del>删除线</del></td>
        </tr>
        <tr>
            <td>[链接](url)</td>
            <td><a href="#">链接</a></td>
        </tr>
        <tr>
            <td>![图片](url)</td>
            <td><img src="#" alt="图片" style="max-width:50px"></td>
        </tr>
        <tr>
            <td>`代码`</td>
            <td><code>代码</code></td>
        </tr>
        <tr>
            <td>```<br>代码块<br>```</td>
            <td><pre><code>代码块</code></pre></td>
        </tr>
        <tr>
            <td>- 列表</td>
            <td><ul><li>列表项</li></ul></td>
        </tr>
        <tr>
            <td>1. 有序列表</td>
            <td><ol><li>列表项</li></ol></td>
        </tr>
        <tr>
            <td>> 引用</td>
            <td><blockquote>引用内容</blockquote></td>
        </tr>
        <tr>
            <td>---</td>
            <td><hr></td>
        </tr>
    </table>
</div>
HTML;
}
?>

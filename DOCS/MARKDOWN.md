# Markdown 功能使用指南

HotTextForum 现已支持 **Markdown 语法**！您可以在发帖、回复时使用 Markdown 格式化文本。

---

## 🚀 如何使用

### 自动检测

系统会自动检测您的内容是否包含 Markdown 语法：

- 如果检测到 Markdown 语法 → 自动使用 Markdown 渲染
- 如果没有 Markdown 语法 → 使用传统 BBCode 渲染

### 编辑器增强

在发帖页面的 textarea 添加 `data-markdown` 属性即可启用 Markdown 编辑器：

```html
<textarea name="atc_content" data-markdown="true" 
          data-markdown-preview="true" 
          data-markdown-help="true"
          data-markdown-autosave="post_draft"></textarea>
```

---

## 📝 Markdown 语法

### 标题

```markdown
# 一级标题
## 二级标题
### 三级标题
```

### 文本格式

```markdown
**粗体文字**
*斜体文字*
~~删除线~~
`代码`
```

### 链接和图片

```markdown
[链接文字](https://example.com)
![图片描述](https://example.com/image.jpg)
```

### 列表

```markdown
- 无序列表项 1
- 无序列表项 2

1. 有序列表项 1
2. 有序列表项 2
```

### 引用

```markdown
> 这是引用内容
> 可以多行
```

### 代码块

````markdown
```php
<?php
echo "Hello World!";
?>
```
````

### 分隔线

```markdown
---
```

---

## 🛠️ 集成到页面

### 1. 引入资源文件

在 `header_responsive.php` 或页面头部添加：

```php
<!-- Markdown Support -->
<link rel="stylesheet" href="style/markdown.css">
```

在页面底部（`footer_responsive.php` 之前）添加：

```php
<!-- Markdown Editor & Renderer -->
<script src="style/markdown.js"></script>
```

### 2. 修改发帖表单

找到发帖页面（如 `post.php`）的 textarea，添加 `data-markdown` 属性：

```html
<textarea name="atc_content" 
          rows="15" 
          cols="80"
          data-markdown="true"
          data-markdown-preview="true"
          data-markdown-help="true"
          data-markdown-autosave="post_<?php echo $fid; ?>"></textarea>
```

**属性说明：**
- `data-markdown="true"` - 启用 Markdown 编辑器
- `data-markdown-preview="true"` - 启用实时预览
- `data-markdown-help="true"` - 显示语法帮助
- `data-markdown-autosave="key"` - 启用自动保存（使用唯一键）

### 3. 渲染已发布内容

显示帖子内容时，`convert()` 函数会自动检测并渲染 Markdown。

如果需要手动渲染，可以使用：

```php
// 自动检测
echo convert($content, $allow_settings);

// 或直接使用 Markdown
if (is_markdown($content)) {
    echo '<div class="markdown-content">' . markdown_to_html($content) . '</div>';
}
```

---

## 🎨 自定义样式

Markdown 样式在 `style/markdown.css` 中定义，您可以根据需要修改：

```css
.markdown-content {
    /* 自定义样式 */
}

.markdown-content h1 {
    /* 标题样式 */
}

.markdown-content code {
    /* 代码样式 */
}
```

---

## 🔧 编辑器工具栏

Markdown 编辑器提供可视化工具栏，包含以下按钮：

| 按钮 | 功能 | 语法 |
|------|------|------|
| **B** | 粗体 | `**text**` |
| **I** | 斜体 | `*text*` |
| **S** | 删除线 | `~~text~~` |
| **H1** | 一级标题 | `# text` |
| **H2** | 二级标题 | `## text` |
| **H3** | 三级标题 | `### text` |
| **Link** | 链接 | `[text](url)` |
| **Img** | 图片 | `![alt](url)` |
| **Code** | 行内代码 | `` `code` `` |
| **Block** | 代码块 | ` ```code``` ` |
| **Quote** | 引用 | `> text` |
| **List** | 无序列表 | `- text` |
| **OL** | 有序列表 | `1. text` |
| **HR** | 分隔线 | `---` |

---

## ⚡ 功能特性

### ✅ 已实现

- ✅ **自动检测**：智能识别 Markdown 语法
- ✅ **双模式**：支持 Markdown 和 BBCode 共存
- ✅ **实时预览**：边写边看效果
- ✅ **工具栏**：可视化编辑按钮
- ✅ **语法帮助**：内置帮助文档
- ✅ **自动保存**：LocalStorage 草稿保存
- ✅ **XSS 防护**：输出自动转义
- ✅ **响应式**：移动端友好
- ✅ **暗黑模式**：自动适配系统主题

### 🎯 支持的语法

- ✅ 标题（H1-H6）
- ✅ 粗体、斜体、删除线
- ✅ 链接、图片
- ✅ 行内代码、代码块
- ✅ 无序列表、有序列表
- ✅ 引用块
- ✅ 分隔线
- ✅ 段落、换行

---

## 📱 移动端支持

Markdown 编辑器完全支持移动设备：

- ✅ 响应式工具栏（自动换行）
- ✅ 触摸友好的按钮（最小 44x44px）
- ✅ 自适应字体大小
- ✅ 移动端预览优化

---

## 🔒 安全性

### XSS 防护

所有 Markdown 内容在渲染前会进行 HTML 转义：

```php
// 自动转义所有 HTML 标签
$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
```

### 内容过滤

系统会过滤潜在的危险内容：

- ✅ `<script>` 标签被转义
- ✅ `javascript:` 协议被过滤
- ✅ `on*` 事件属性被移除

---

## 📊 性能

### 服务端渲染

PHP Markdown 解析器轻量高效：

- 纯 PHP 实现，无外部依赖
- 单文件 ~200 行代码
- 性能损耗 < 1ms

### 客户端预览

JavaScript 渲染器用于实时预览：

- 防抖延迟 300ms
- 增量渲染
- 不影响表单提交

---

## 🧪 测试

### 测试 Markdown 检测

```php
// 测试自动检测
$text1 = "# 标题\n这是 Markdown";
var_dump(is_markdown($text1)); // true

$text2 = "[b]粗体[/b]";
var_dump(is_markdown($text2)); // false
```

### 测试渲染

```php
$markdown = "**粗体** 和 *斜体*";
echo markdown_to_html($markdown);
// 输出: <p><strong>粗体</strong> 和 <em>斜体</em></p>
```

---

## 📖 示例：完整集成

### post.php（发帖页面）

```php
<?php
require './global.php';
require './require/bbscode.php'; // 已包含 Markdown 支持

// ... 其他代码 ...

require './header_responsive.php';
?>

<!-- Markdown CSS -->
<link rel="stylesheet" href="style/markdown.css">

<form method="post" action="post.php">
    <textarea name="atc_content" 
              data-markdown="true"
              data-markdown-preview="true"
              data-markdown-help="true"
              data-markdown-autosave="post_<?php echo $fid; ?>"></textarea>
    
    <button type="submit">发布</button>
</form>

<!-- Markdown JS -->
<script src="style/markdown.js"></script>

<?php require './footer_responsive.php'; ?>
```

### read.php（阅读页面）

```php
<?php
// 渲染帖子内容（自动检测 Markdown）
$post_content = convert($atc_content, $db_htfpic);
?>

<div class="post-content">
    <?php echo $post_content; ?>
</div>
```

---

## ❓ 常见问题

### Q: Markdown 和 BBCode 可以混用吗？

A: 不建议。系统会自动检测，如果检测到 Markdown 语法则使用 Markdown 渲染，否则使用 BBCode。

### Q: 如何禁用 Markdown？

A: 移除 textarea 的 `data-markdown` 属性即可。内容仍会自动检测。

### Q: 代码高亮支持吗？

A: 基础版本不包含语法高亮。如需高亮，可引入 Prism.js 或 Highlight.js。

### Q: 支持 GFM（GitHub Flavored Markdown）吗？

A: 当前支持基础 Markdown 语法。GFM 扩展（表格、任务列表）可后续添加。

---

## 🔄 升级说明

Markdown 功能是向下兼容的：

- ✅ 现有 BBCode 内容不受影响
- ✅ 新内容可选择使用 Markdown
- ✅ 两种语法可以并存

---

## 📚 相关文件

| 文件 | 说明 |
|------|------|
| `require/markdown.php` | PHP Markdown 解析器（服务端）|
| `require/bbscode.php` | BBCode 解析器（已集成 Markdown）|
| `style/markdown.css` | Markdown 样式文件 |
| `style/markdown.js` | Markdown 编辑器（客户端）|

---

**Markdown 功能已完成** - 享受更好的写作体验！  
更新时间：2026-06-17

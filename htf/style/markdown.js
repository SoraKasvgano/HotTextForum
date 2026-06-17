/**
 * Markdown Editor & Renderer for HotTextForum
 * Client-side Markdown preview and syntax helper
 */

(function(window, document) {
  'use strict';

  var HTF = window.HTF || {};
  window.HTF = HTF;

  // ==================== Markdown Editor ====================

  HTF.MarkdownEditor = {
    /**
     * Initialize Markdown editor on textarea
     * @param {string|HTMLElement} textarea - Textarea element or selector
     * @param {Object} options - Configuration options
     */
    init: function(textarea, options) {
      if (typeof textarea === 'string') {
        textarea = document.querySelector(textarea);
      }
      if (!textarea) return;

      options = options || {};

      // Create toolbar
      var toolbar = this.createToolbar(textarea);
      textarea.parentNode.insertBefore(toolbar, textarea);

      // Add CSS class
      textarea.classList.add('markdown-textarea');

      // Live preview (optional)
      if (options.preview !== false) {
        this.enablePreview(textarea);
      }

      // Syntax help
      if (options.help !== false) {
        this.addHelpButton(textarea);
      }

      // Auto-save
      if (options.autoSave) {
        this.enableAutoSave(textarea, options.autoSave);
      }
    },

    /**
     * Create toolbar with Markdown buttons
     */
    createToolbar: function(textarea) {
      var toolbar = document.createElement('div');
      toolbar.className = 'markdown-editor-toolbar';

      var buttons = [
        {label: 'B', title: '粗体', syntax: '**', icon: '**'},
        {label: 'I', title: '斜体', syntax: '*', icon: '*'},
        {label: 'S', title: '删除线', syntax: '~~', icon: '~~'},
        {label: 'H1', title: '一级标题', syntax: '# ', prefix: true},
        {label: 'H2', title: '二级标题', syntax: '## ', prefix: true},
        {label: 'H3', title: '三级标题', syntax: '### ', prefix: true},
        {label: 'Link', title: '链接', template: '[链接文字](url)'},
        {label: 'Img', title: '图片', template: '![图片描述](url)'},
        {label: 'Code', title: '代码', syntax: '`', icon: '`'},
        {label: 'Block', title: '代码块', template: '```\n代码\n```\n'},
        {label: 'Quote', title: '引用', syntax: '> ', prefix: true},
        {label: 'List', title: '列表', syntax: '- ', prefix: true},
        {label: 'OL', title: '有序列表', syntax: '1. ', prefix: true},
        {label: 'HR', title: '分隔线', template: '\n---\n'}
      ];

      var self = this;
      buttons.forEach(function(btn) {
        var button = document.createElement('button');
        button.type = 'button';
        button.textContent = btn.label;
        button.title = btn.title;
        button.onclick = function(e) {
          e.preventDefault();
          self.insertSyntax(textarea, btn);
        };
        toolbar.appendChild(button);
      });

      return toolbar;
    },

    /**
     * Insert Markdown syntax at cursor
     */
    insertSyntax: function(textarea, btn) {
      var start = textarea.selectionStart;
      var end = textarea.selectionEnd;
      var text = textarea.value;
      var selectedText = text.substring(start, end);

      var before = '';
      var after = '';
      var newText = '';

      if (btn.template) {
        // Template mode (link, image, code block)
        newText = btn.template;
        if (selectedText) {
          newText = newText.replace(/链接文字|图片描述|代码/, selectedText);
        }
      } else if (btn.prefix) {
        // Prefix mode (headers, lists, quote)
        var lines = selectedText ? selectedText.split('\n') : [''];
        newText = lines.map(function(line) {
          return btn.syntax + line;
        }).join('\n');
      } else {
        // Wrap mode (bold, italic, code)
        before = btn.syntax;
        after = btn.syntax;
        newText = before + (selectedText || '文字') + after;
      }

      // Insert text
      textarea.value = text.substring(0, start) + newText + text.substring(end);

      // Set cursor position
      var cursorPos = start + newText.length;
      if (!selectedText && btn.template) {
        // Move cursor to placeholder position
        if (btn.template.indexOf('[') !== -1) {
          cursorPos = start + btn.template.indexOf('[') + 1;
        }
      }
      textarea.setSelectionRange(cursorPos, cursorPos);
      textarea.focus();

      // Trigger input event for preview update
      var event = new Event('input', {bubbles: true});
      textarea.dispatchEvent(event);
    },

    /**
     * Enable live preview
     */
    enablePreview: function(textarea) {
      var container = document.createElement('div');
      container.className = 'markdown-preview-container';

      var toggleBtn = document.createElement('button');
      toggleBtn.type = 'button';
      toggleBtn.textContent = '预览';
      toggleBtn.className = 'markdown-preview-toggle-btn';

      var preview = document.createElement('div');
      preview.className = 'markdown-preview markdown-content';
      preview.style.display = 'none';

      container.appendChild(toggleBtn);
      container.appendChild(preview);
      textarea.parentNode.insertBefore(container, textarea.nextSibling);

      var isPreviewMode = false;

      toggleBtn.onclick = function() {
        isPreviewMode = !isPreviewMode;
        if (isPreviewMode) {
          preview.innerHTML = HTF.Markdown.render(textarea.value);
          preview.style.display = 'block';
          textarea.style.display = 'none';
          toggleBtn.textContent = '编辑';
        } else {
          preview.style.display = 'none';
          textarea.style.display = 'block';
          toggleBtn.textContent = '预览';
        }
      };

      // Auto-update preview on input
      textarea.addEventListener('input', HTF.debounce(function() {
        if (isPreviewMode) {
          preview.innerHTML = HTF.Markdown.render(textarea.value);
        }
      }, 300));
    },

    /**
     * Add help button
     */
    addHelpButton: function(textarea) {
      var helpBtn = document.createElement('a');
      helpBtn.className = 'markdown-help-toggle';
      helpBtn.textContent = 'Markdown 语法帮助';
      helpBtn.href = '#';

      var helpDiv = document.createElement('div');
      helpDiv.className = 'markdown-help';
      helpDiv.style.display = 'none';
      helpDiv.innerHTML = this.getHelpHTML();

      textarea.parentNode.insertBefore(helpBtn, textarea);
      textarea.parentNode.insertBefore(helpDiv, textarea);

      helpBtn.onclick = function(e) {
        e.preventDefault();
        helpDiv.style.display = helpDiv.style.display === 'none' ? 'block' : 'none';
      };
    },

    /**
     * Get help HTML
     */
    getHelpHTML: function() {
      return '<h4>Markdown 语法帮助</h4>' +
        '<table class="markdown-help-table">' +
        '<tr><th>语法</th><th>效果</th></tr>' +
        '<tr><td><code># 标题</code></td><td><strong>一级标题</strong></td></tr>' +
        '<tr><td><code>## 标题</code></td><td><strong>二级标题</strong></td></tr>' +
        '<tr><td><code>**粗体**</code></td><td><strong>粗体</strong></td></tr>' +
        '<tr><td><code>*斜体*</code></td><td><em>斜体</em></td></tr>' +
        '<tr><td><code>~~删除线~~</code></td><td><del>删除线</del></td></tr>' +
        '<tr><td><code>[链接](url)</code></td><td><a href="#">链接</a></td></tr>' +
        '<tr><td><code>![图片](url)</code></td><td>图片</td></tr>' +
        '<tr><td><code>`代码`</code></td><td><code>代码</code></td></tr>' +
        '<tr><td><code>```代码块```</code></td><td>代码块</td></tr>' +
        '<tr><td><code>- 列表</code></td><td>• 列表项</td></tr>' +
        '<tr><td><code>1. 列表</code></td><td>1. 列表项</td></tr>' +
        '<tr><td><code>&gt; 引用</code></td><td>引用内容</td></tr>' +
        '<tr><td><code>---</code></td><td>分隔线</td></tr>' +
        '</table>';
    },

    /**
     * Enable auto-save to localStorage
     */
    enableAutoSave: function(textarea, key) {
      // Load saved content
      var saved = localStorage.getItem('markdown_draft_' + key);
      if (saved && !textarea.value.trim()) {
        if (confirm('发现保存的草稿，是否恢复？')) {
          textarea.value = saved;
        }
      }

      // Save on input
      textarea.addEventListener('input', HTF.debounce(function() {
        localStorage.setItem('markdown_draft_' + key, textarea.value);
      }, 1000));

      // Clear on submit
      var form = textarea.form;
      if (form) {
        form.addEventListener('submit', function() {
          localStorage.removeItem('markdown_draft_' + key);
        });
      }
    }
  };

  // ==================== Markdown Renderer ====================

  HTF.Markdown = {
    /**
     * Render Markdown to HTML (client-side)
     * Lightweight implementation for preview
     */
    render: function(text) {
      if (!text) return '';

      // Escape HTML first
      text = this.escapeHtml(text);

      // Code blocks
      text = text.replace(/```(\w*)\n([\s\S]*?)\n```/g, function(match, lang, code) {
        return '<pre><code class="language-' + lang + '">' + code + '</code></pre>';
      });

      // Inline code
      text = text.replace(/`([^`]+)`/g, '<code>$1</code>');

      // Headers
      text = text.replace(/^######\s+(.+)$/gm, '<h6>$1</h6>');
      text = text.replace(/^#####\s+(.+)$/gm, '<h5>$1</h5>');
      text = text.replace(/^####\s+(.+)$/gm, '<h4>$1</h4>');
      text = text.replace(/^###\s+(.+)$/gm, '<h3>$1</h3>');
      text = text.replace(/^##\s+(.+)$/gm, '<h2>$1</h2>');
      text = text.replace(/^#\s+(.+)$/gm, '<h1>$1</h1>');

      // Bold
      text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
      text = text.replace(/__(.+?)__/g, '<strong>$1</strong>');

      // Italic
      text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');
      text = text.replace(/_(.+?)_/g, '<em>$1</em>');

      // Strikethrough
      text = text.replace(/~~(.+?)~~/g, '<del>$1</del>');

      // Links
      text = text.replace(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');

      // Images
      text = text.replace(/!\[([^\]]*)\]\(([^\)]+)\)/g, '<img src="$2" alt="$1" class="markdown-img">');

      // Lists
      text = text.replace(/^\* (.+)$/gm, '<li>$1</li>');
      text = text.replace(/^- (.+)$/gm, '<li>$1</li>');
      text = text.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');

      text = text.replace(/^\d+\. (.+)$/gm, '<li>$1</li>');

      // Blockquotes
      text = text.replace(/^> (.+)$/gm, '<blockquote>$1</blockquote>');

      // HR
      text = text.replace(/^---$/gm, '<hr>');

      // Paragraphs
      text = text.replace(/\n\n/g, '</p><p>');
      text = '<p>' + text + '</p>';

      // Line breaks
      text = text.replace(/\n/g, '<br>');

      // Clean up
      text = text.replace(/<p><\/p>/g, '');
      text = text.replace(/<p>\s*<\/p>/g, '');

      return text;
    },

    escapeHtml: function(text) {
      var div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
  };

  // ==================== Auto-init ====================

  HTF.ready(function() {
    // Auto-init Markdown editor on textareas with data-markdown attribute
    var textareas = document.querySelectorAll('textarea[data-markdown]');
    textareas.forEach(function(textarea) {
      HTF.MarkdownEditor.init(textarea, {
        preview: textarea.dataset.markdownPreview !== 'false',
        help: textarea.dataset.markdownHelp !== 'false',
        autoSave: textarea.dataset.markdownAutosave || null
      });
    });
  });

  window.HTF = HTF;

})(window, document);

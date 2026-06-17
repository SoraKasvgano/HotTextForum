/**
 * HotTextForum Modern JavaScript Library
 * ES6+ with IE11 fallback support
 * Security-hardened, Mobile-optimized
 */

(function(window, document) {
  'use strict';

  // ==================== Namespace ====================
  var HTF = window.HTF || {};
  window.HTF = HTF;

  // ==================== Configuration ====================
  HTF.config = {
    csrfToken: window.csrfToken || '',
    apiBase: window.location.origin + window.location.pathname.replace(/[^\/]*$/, ''),
    imageLoadDelay: 300,
    autoSaveInterval: 30000,
    debounceDelay: 300
  };

  // ==================== Utility Functions ====================

  /**
   * Debounce function execution
   */
  HTF.debounce = function(func, wait) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        func.apply(context, args);
      }, wait);
    };
  };

  /**
   * Throttle function execution
   */
  HTF.throttle = function(func, limit) {
    var inThrottle;
    return function() {
      var args = arguments;
      var context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(function() { inThrottle = false; }, limit);
      }
    };
  };

  /**
   * XSS-safe HTML escape
   */
  HTF.escape = function(str) {
    if (typeof str !== 'string') return '';
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  };

  /**
   * Safe HTML unescape (for displaying escaped content)
   */
  HTF.unescape = function(str) {
    if (typeof str !== 'string') return '';
    var div = document.createElement('div');
    div.innerHTML = str;
    return div.textContent || div.innerText || '';
  };

  /**
   * Deep clone object (simple version)
   */
  HTF.clone = function(obj) {
    if (obj === null || typeof obj !== 'object') return obj;
    if (obj instanceof Date) return new Date(obj.getTime());
    if (obj instanceof Array) return obj.map(function(item) { return HTF.clone(item); });
    var cloned = {};
    for (var key in obj) {
      if (obj.hasOwnProperty(key)) {
        cloned[key] = HTF.clone(obj[key]);
      }
    }
    return cloned;
  };

  // ==================== AJAX ====================

  /**
   * Secure AJAX request with CSRF token
   */
  HTF.ajax = function(url, options) {
    options = options || {};
    options.method = (options.method || 'GET').toUpperCase();
    options.headers = options.headers || {};
    options.credentials = options.credentials || 'same-origin';

    // Add CSRF token for POST/PUT/DELETE
    if (['POST', 'PUT', 'DELETE'].indexOf(options.method) !== -1) {
      if (options.data && !(options.data instanceof FormData)) {
        options.data = options.data || {};
        options.data.csrf_token = HTF.config.csrfToken;
      } else if (options.data instanceof FormData) {
        options.data.append('csrf_token', HTF.config.csrfToken);
      }
    }

    // Convert data to appropriate format
    if (options.data && options.method !== 'GET') {
      if (!(options.data instanceof FormData)) {
        var formData = new FormData();
        for (var key in options.data) {
          if (options.data.hasOwnProperty(key)) {
            formData.append(key, options.data[key]);
          }
        }
        options.body = formData;
      } else {
        options.body = options.data;
      }
      delete options.data;
    }

    // Add X-Requested-With header
    options.headers['X-Requested-With'] = 'XMLHttpRequest';

    return fetch(url, options)
      .then(function(response) {
        if (!response.ok) {
          throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        var contentType = response.headers.get('content-type');
        if (contentType && contentType.indexOf('application/json') !== -1) {
          return response.json();
        }
        return response.text();
      })
      .catch(function(error) {
        console.error('AJAX Error:', error);
        if (options.onError) {
          options.onError(error);
        }
        throw error;
      });
  };

  // ==================== Form Validation ====================

  HTF.validate = {
    required: function(value) {
      return value !== null && value !== undefined && String(value).trim().length > 0;
    },

    email: function(email) {
      var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(String(email).toLowerCase());
    },

    username: function(username, min, max) {
      min = min || 3;
      max = max || 20;
      var len = String(username).length;
      if (len < min || len > max) return false;
      return /^[a-zA-Z0-9_-]+$/.test(username);
    },

    password: function(password, min) {
      min = min || 6;
      return String(password).length >= min;
    },

    url: function(url) {
      try {
        new URL(url);
        return true;
      } catch(e) {
        return false;
      }
    },

    number: function(value, min, max) {
      var num = parseFloat(value);
      if (isNaN(num)) return false;
      if (min !== undefined && num < min) return false;
      if (max !== undefined && num > max) return false;
      return true;
    },

    integer: function(value) {
      return /^-?\d+$/.test(String(value));
    }
  };

  /**
   * Form validation helper
   */
  HTF.validateForm = function(formElement, rules) {
    var errors = {};
    var isValid = true;

    for (var fieldName in rules) {
      if (!rules.hasOwnProperty(fieldName)) continue;

      var field = formElement.elements[fieldName];
      if (!field) continue;

      var value = field.value;
      var fieldRules = rules[fieldName];

      for (var i = 0; i < fieldRules.length; i++) {
        var rule = fieldRules[i];
        var validator = rule.validator;
        var params = rule.params || [];
        var message = rule.message || '验证失败';

        var valid = false;
        if (typeof validator === 'string' && HTF.validate[validator]) {
          valid = HTF.validate[validator].apply(null, [value].concat(params));
        } else if (typeof validator === 'function') {
          valid = validator(value, field, formElement);
        }

        if (!valid) {
          errors[fieldName] = message;
          isValid = false;
          break;
        }
      }
    }

    return {
      valid: isValid,
      errors: errors
    };
  };

  // ==================== Auto-save Draft ====================

  HTF.autoSave = {
    timers: {},

    start: function(formId, key, interval) {
      interval = interval || HTF.config.autoSaveInterval;
      var form = document.getElementById(formId);
      if (!form) return;

      var textarea = form.querySelector('textarea[name="message"], textarea[name="atc_content"], textarea[name="content"]');
      if (!textarea) return;

      // Load existing draft
      this.load(key, textarea);

      // Save periodically
      this.timers[key] = setInterval(function() {
        HTF.autoSave.save(key, textarea);
      }, interval);

      // Save on form submit
      form.addEventListener('submit', function() {
        HTF.autoSave.clear(key);
      });

      // Save on page unload
      window.addEventListener('beforeunload', function() {
        HTF.autoSave.save(key, textarea);
      });
    },

    save: function(key, textarea) {
      var value = textarea.value.trim();
      if (value.length > 0) {
        try {
          localStorage.setItem('htf_draft_' + key, value);
          localStorage.setItem('htf_draft_time_' + key, Date.now());
        } catch(e) {
          console.warn('LocalStorage save failed:', e);
        }
      }
    },

    load: function(key, textarea) {
      try {
        var draft = localStorage.getItem('htf_draft_' + key);
        var draftTime = localStorage.getItem('htf_draft_time_' + key);

        if (draft && textarea.value.trim() === '') {
          var timeAgo = Math.floor((Date.now() - parseInt(draftTime)) / 60000);
          var message = '发现 ' + timeAgo + ' 分钟前的草稿，是否恢复？';

          if (confirm(message)) {
            textarea.value = draft;
            return true;
          }
        }
      } catch(e) {
        console.warn('LocalStorage load failed:', e);
      }
      return false;
    },

    clear: function(key) {
      if (this.timers[key]) {
        clearInterval(this.timers[key]);
        delete this.timers[key];
      }
      try {
        localStorage.removeItem('htf_draft_' + key);
        localStorage.removeItem('htf_draft_time_' + key);
      } catch(e) {
        console.warn('LocalStorage clear failed:', e);
      }
    }
  };

  // ==================== Image Lazy Loading ====================

  HTF.lazyLoad = {
    observer: null,

    init: function(selector) {
      selector = selector || 'img[data-src]';
      var images = document.querySelectorAll(selector);

      if ('IntersectionObserver' in window) {
        this.observer = new IntersectionObserver(function(entries) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var img = entry.target;
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
              HTF.lazyLoad.observer.unobserve(img);
            }
          });
        }, {
          rootMargin: '50px'
        });

        images.forEach(function(img) {
          HTF.lazyLoad.observer.observe(img);
        });
      } else {
        // Fallback for old browsers
        images.forEach(function(img) {
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
        });
      }
    }
  };

  // ==================== Modal Dialog ====================

  HTF.modal = {
    show: function(options) {
      options = options || {};
      var title = options.title || '提示';
      var content = options.content || '';
      var buttons = options.buttons || [{label: '确定', primary: true}];

      var overlay = document.createElement('div');
      overlay.className = 'htf-modal-overlay';
      overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center';

      var modal = document.createElement('div');
      modal.className = 'htf-modal';
      modal.style.cssText = 'background:#fff;border-radius:8px;max-width:500px;width:90%;max-height:80vh;overflow:auto;box-shadow:0 4px 16px rgba(0,0,0,0.2)';

      var header = document.createElement('div');
      header.style.cssText = 'padding:16px 24px;border-bottom:1px solid #e8e8e8;font-weight:700';
      header.textContent = title;

      var body = document.createElement('div');
      body.style.cssText = 'padding:24px';
      body.innerHTML = content;

      var footer = document.createElement('div');
      footer.style.cssText = 'padding:16px 24px;border-top:1px solid #e8e8e8;text-align:right';

      buttons.forEach(function(btn) {
        var button = document.createElement('button');
        button.textContent = btn.label;
        button.className = btn.primary ? 'btn btn-primary' : 'btn btn-secondary';
        button.style.cssText = 'margin-left:8px';
        button.onclick = function() {
          if (btn.onClick) btn.onClick();
          HTF.modal.close(overlay);
        };
        footer.appendChild(button);
      });

      modal.appendChild(header);
      modal.appendChild(body);
      modal.appendChild(footer);
      overlay.appendChild(modal);
      document.body.appendChild(overlay);

      overlay.onclick = function(e) {
        if (e.target === overlay) {
          HTF.modal.close(overlay);
        }
      };

      return overlay;
    },

    close: function(overlay) {
      if (overlay && overlay.parentNode) {
        overlay.parentNode.removeChild(overlay);
      }
    },

    alert: function(message, title) {
      return this.show({
        title: title || '提示',
        content: HTF.escape(message),
        buttons: [{label: '确定', primary: true}]
      });
    },

    confirm: function(message, onConfirm, onCancel) {
      return this.show({
        title: '确认',
        content: HTF.escape(message),
        buttons: [
          {label: '取消', onClick: onCancel},
          {label: '确定', primary: true, onClick: onConfirm}
        ]
      });
    }
  };

  // ==================== Toast Notification ====================

  HTF.toast = function(message, type, duration) {
    type = type || 'info';
    duration = duration || 3000;

    var colors = {
      success: '#52c41a',
      error: '#ff4d4f',
      warning: '#faad14',
      info: '#1890ff'
    };

    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:' + colors[type] + ';color:#fff;padding:12px 24px;border-radius:4px;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:10000;max-width:90%;animation:htf-toast-in 0.3s ease';
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(function() {
      toast.style.animation = 'htf-toast-out 0.3s ease';
      setTimeout(function() {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, duration);
  };

  // Add toast animations
  var style = document.createElement('style');
  style.textContent = '@keyframes htf-toast-in { from { opacity:0; transform:translateX(-50%) translateY(-20px); } to { opacity:1; transform:translateX(-50%) translateY(0); } } @keyframes htf-toast-out { from { opacity:1; } to { opacity:0; } }';
  document.head.appendChild(style);

  // ==================== DOM Ready ====================

  HTF.ready = function(callback) {
    if (document.readyState !== 'loading') {
      callback();
    } else {
      document.addEventListener('DOMContentLoaded', callback);
    }
  };

  // ==================== Export ====================

  window.HTF = HTF;

})(window, document);

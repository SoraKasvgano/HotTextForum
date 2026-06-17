  </div><!-- /.container -->
</main><!-- /.main-content -->

<!-- Site Footer -->
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-content" style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem">

      <!-- Footer Links -->
      <nav class="footer-nav" aria-label="页脚导航">
        <a href="index.php" style="margin:0 0.75rem;color:#595959">首页</a>
        <a href="bbsfaq.php" style="margin:0 0.75rem;color:#595959">帮助</a>
        <a href="javascript:void(0)" onclick="window.scrollTo({top:0,behavior:'smooth'})" style="margin:0 0.75rem;color:#595959">返回顶部</a>
      </nav>

      <!-- Copyright -->
      <div class="footer-copyright" style="font-size:0.875rem">
        <p style="margin:0.25rem 0">
          Powered by <a href="http://www.hotscripts.com" target="_blank" rel="noopener" style="color:#1890ff">HotTextForum</a> <?php echo isset($db_version) ? $db_version : ''; ?>
        </p>
        <p style="margin:0.25rem 0;color:#999">
          &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($db_bbsname, ENT_QUOTES, 'UTF-8'); ?>. All rights reserved.
        </p>
        <?php if(isset($db_icp) && !empty($db_icp)): ?>
        <p style="margin:0.25rem 0;color:#999">
          <?php echo htmlspecialchars($db_icp, ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <?php endif; ?>
      </div>

      <!-- Statistics (optional) -->
      <?php if(isset($show_stats) && $show_stats): ?>
      <div class="footer-stats" style="font-size:0.875rem;color:#999">
        <span>在线: <?php echo isset($usertotal) ? $usertotal : 0; ?></span>
        <span style="margin:0 0.5rem">|</span>
        <span>会员: <?php echo isset($bbstotleuser) ? $bbstotleuser : 0; ?></span>
        <span style="margin:0 0.5rem">|</span>
        <span>主题: <?php echo isset($bbstpc) ? $bbstpc : 0; ?></span>
      </div>
      <?php endif; ?>
    </div>

    <!-- Performance Info (only for admin) -->
    <?php if(isset($groupid) && ($groupid == 'manager' || $groupid == 'superadmin')): ?>
    <div class="footer-debug" style="margin-top:1rem;padding-top:1rem;border-top:1px solid #d9d9d9;font-size:0.75rem;color:#999;text-align:center">
      <?php
      $page_end_time = microtime(true);
      $page_exec_time = isset($page_start_time) ? round(($page_end_time - $page_start_time) * 1000, 2) : 0;
      ?>
      页面执行时间: <?php echo $page_exec_time; ?>ms
      <?php if(function_exists('memory_get_peak_usage')): ?>
      | 内存峰值: <?php echo round(memory_get_peak_usage() / 1024 / 1024, 2); ?>MB
      <?php endif; ?>
      | PHP: <?php echo PHP_VERSION; ?>
    </div>
    <?php endif; ?>
  </div>
</footer>

<!-- Back to Top Button (mobile) -->
<button id="back-to-top" class="back-to-top"
        style="display:none;position:fixed;bottom:20px;right:20px;width:48px;height:48px;background:#1890ff;color:#fff;border:none;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;z-index:1000;font-size:20px"
        aria-label="返回顶部"
        title="返回顶部">
  ↑
</button>

<!-- Core JavaScript -->
<script>
(function() {
  'use strict';

  // Back to top button
  var backToTop = document.getElementById('back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTop.style.display = 'block';
      } else {
        backToTop.style.display = 'none';
      }
    });

    backToTop.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  // Lazy load images
  if ('IntersectionObserver' in window) {
    var lazyImages = document.querySelectorAll('img[data-src]');
    var imageObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    });

    lazyImages.forEach(function(img) {
      imageObserver.observe(img);
    });
  } else {
    // Fallback for old browsers
    var lazyImages = document.querySelectorAll('img[data-src]');
    lazyImages.forEach(function(img) {
      img.src = img.dataset.src;
      img.removeAttribute('data-src');
    });
  }

  // External links security
  var externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="' + window.location.hostname + '"])');
  externalLinks.forEach(function(link) {
    if (!link.hasAttribute('rel')) {
      link.setAttribute('rel', 'noopener noreferrer');
    }
    if (!link.hasAttribute('target')) {
      link.setAttribute('target', '_blank');
    }
  });

  // Form validation helper
  window.htfValidate = {
    email: function(email) {
      var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },
    username: function(username, min, max) {
      min = min || 3;
      max = max || 20;
      if (username.length < min || username.length > max) return false;
      return /^[a-zA-Z0-9_-]+$/.test(username);
    },
    required: function(value) {
      return value.trim().length > 0;
    }
  };

  // XSS protection for dynamic content
  window.htfEscape = function(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  };

  // AJAX helper with CSRF token
  window.htfAjax = function(url, options) {
    options = options || {};
    options.method = options.method || 'POST';
    options.headers = options.headers || {};
    options.headers['X-Requested-With'] = 'XMLHttpRequest';

    // Add CSRF token for POST requests
    if (options.method.toUpperCase() === 'POST' && typeof csrfToken !== 'undefined') {
      if (options.data instanceof FormData) {
        options.data.append('csrf_token', csrfToken);
      } else {
        options.data = options.data || {};
        options.data.csrf_token = csrfToken;
      }
    }

    // Convert data to FormData or URLSearchParams
    if (options.data && !(options.data instanceof FormData)) {
      var formData = new FormData();
      for (var key in options.data) {
        if (options.data.hasOwnProperty(key)) {
          formData.append(key, options.data[key]);
        }
      }
      options.body = formData;
    } else if (options.data) {
      options.body = options.data;
    }

    delete options.data;

    return fetch(url, options)
      .then(function(response) {
        if (!response.ok) {
          throw new Error('HTTP error ' + response.status);
        }
        return response.json ? response.json() : response.text();
      })
      .catch(function(error) {
        console.error('AJAX Error:', error);
        throw error;
      });
  };

  // Auto-save draft (for post/reply forms)
  var autoSaveInterval;
  window.htfAutoSave = function(formId, key) {
    var form = document.getElementById(formId);
    if (!form) return;

    var textarea = form.querySelector('textarea[name="message"], textarea[name="atc_content"]');
    if (!textarea) return;

    // Load draft
    var draft = localStorage.getItem('htf_draft_' + key);
    if (draft && textarea.value.trim() === '') {
      if (confirm('发现未提交的草稿，是否恢复？')) {
        textarea.value = draft;
      }
    }

    // Save draft every 30 seconds
    autoSaveInterval = setInterval(function() {
      if (textarea.value.trim().length > 0) {
        localStorage.setItem('htf_draft_' + key, textarea.value);
      }
    }, 30000);

    // Clear draft on submit
    form.addEventListener('submit', function() {
      localStorage.removeItem('htf_draft_' + key);
      clearInterval(autoSaveInterval);
    });
  };

  // Touch-friendly improvements for mobile
  if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');

    // Add touch feedback
    document.addEventListener('touchstart', function() {}, {passive: true});
  }

  // Accessibility: keyboard navigation for dropdowns
  var dropdowns = document.querySelectorAll('[aria-haspopup="true"]');
  dropdowns.forEach(function(dropdown) {
    dropdown.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
      }
    });
  });

  // Performance: report long tasks (for debugging)
  if (window.PerformanceObserver && <?php echo $groupid === 'manager' ? 'true' : 'false'; ?>) {
    try {
      var observer = new PerformanceObserver(function(list) {
        list.getEntries().forEach(function(entry) {
          if (entry.duration > 50) {
            console.warn('Long task detected:', entry);
          }
        });
      });
      observer.observe({entryTypes: ['longtask']});
    } catch(e) {
      // Browser doesn't support longtask
    }
  }
})();
</script>

<!-- Custom page scripts -->
<?php if(isset($page_scripts)): ?>
<?php echo $page_scripts; ?>
<?php endif; ?>

</body>
</html>

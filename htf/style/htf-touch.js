/**
 * Mobile Touch Optimization for HotTextForum
 * Gestures, Swipes, Touch-friendly interactions
 */

(function(window, document) {
  'use strict';

  var HTF = window.HTF || {};
  window.HTF = HTF;

  // ==================== Touch Detection ====================

  HTF.touch = {
    enabled: 'ontouchstart' in window,
    isTouch: function() {
      return this.enabled;
    }
  };

  // Add touch class to body
  if (HTF.touch.enabled) {
    document.documentElement.classList.add('touch-device');
  }

  // ==================== Swipe Gesture ====================

  HTF.swipe = {
    threshold: 50, // minimum distance for swipe
    timeout: 300,  // maximum time for swipe

    init: function(element, options) {
      options = options || {};
      var threshold = options.threshold || this.threshold;
      var timeout = options.timeout || this.timeout;

      var startX, startY, startTime;
      var tracking = false;

      element.addEventListener('touchstart', function(e) {
        var touch = e.touches[0];
        startX = touch.clientX;
        startY = touch.clientY;
        startTime = Date.now();
        tracking = true;
      }, {passive: true});

      element.addEventListener('touchmove', function(e) {
        if (!tracking) return;
        // Allow native scrolling
      }, {passive: true});

      element.addEventListener('touchend', function(e) {
        if (!tracking) return;
        tracking = false;

        var touch = e.changedTouches[0];
        var deltaX = touch.clientX - startX;
        var deltaY = touch.clientY - startY;
        var deltaTime = Date.now() - startTime;

        // Check if it's a swipe (not just tap)
        if (deltaTime > timeout) return;
        if (Math.abs(deltaX) < threshold && Math.abs(deltaY) < threshold) return;

        // Determine direction
        var direction;
        if (Math.abs(deltaX) > Math.abs(deltaY)) {
          direction = deltaX > 0 ? 'right' : 'left';
        } else {
          direction = deltaY > 0 ? 'down' : 'up';
        }

        // Fire callback
        if (options['on' + direction]) {
          e.preventDefault();
          options['on' + direction](e, {deltaX: deltaX, deltaY: deltaY, deltaTime: deltaTime});
        }
      });
    }
  };

  // ==================== Swipe-to-back (Browser history) ====================

  HTF.swipeBack = {
    enabled: false,
    edgeThreshold: 50, // pixels from edge to trigger

    init: function() {
      if (this.enabled || !HTF.touch.enabled) return;
      this.enabled = true;

      var startX, fromEdge;

      document.addEventListener('touchstart', function(e) {
        var touch = e.touches[0];
        startX = touch.clientX;
        fromEdge = startX < HTF.swipeBack.edgeThreshold;
      }, {passive: true});

      document.addEventListener('touchend', function(e) {
        if (!fromEdge) return;

        var touch = e.changedTouches[0];
        var deltaX = touch.clientX - startX;

        // Swipe right from left edge = back
        if (deltaX > 100) {
          window.history.back();
        }
      });
    }
  };

  // ==================== Pull-to-refresh ====================

  HTF.pullToRefresh = {
    threshold: 100,
    enabled: false,

    init: function(options) {
      if (this.enabled || !HTF.touch.enabled) return;
      this.enabled = true;

      options = options || {};
      var threshold = options.threshold || this.threshold;
      var onRefresh = options.onRefresh || function() { location.reload(); };

      var startY, currentY, pulling = false;
      var indicator = this.createIndicator();

      document.addEventListener('touchstart', function(e) {
        if (window.pageYOffset !== 0) return;
        startY = e.touches[0].clientY;
      }, {passive: true});

      document.addEventListener('touchmove', function(e) {
        if (startY === null || window.pageYOffset !== 0) return;

        currentY = e.touches[0].clientY;
        var pullDistance = currentY - startY;

        if (pullDistance > 0) {
          pulling = true;
          indicator.style.opacity = Math.min(pullDistance / threshold, 1);
          indicator.style.transform = 'translateY(' + Math.min(pullDistance, threshold) + 'px)';

          if (pullDistance > threshold) {
            indicator.textContent = '释放刷新';
          } else {
            indicator.textContent = '下拉刷新';
          }
        }
      }, {passive: true});

      document.addEventListener('touchend', function(e) {
        if (!pulling) return;
        pulling = false;

        var pullDistance = currentY - startY;
        if (pullDistance > threshold) {
          indicator.textContent = '刷新中...';
          onRefresh();
          setTimeout(function() {
            indicator.style.opacity = 0;
            indicator.style.transform = 'translateY(-50px)';
          }, 500);
        } else {
          indicator.style.opacity = 0;
          indicator.style.transform = 'translateY(-50px)';
        }

        startY = null;
      });
    },

    createIndicator: function() {
      var indicator = document.createElement('div');
      indicator.id = 'pull-to-refresh-indicator';
      indicator.style.cssText = 'position:fixed;top:0;left:50%;transform:translateX(-50%) translateY(-50px);background:rgba(0,0,0,0.7);color:#fff;padding:8px 16px;border-radius:0 0 8px 8px;font-size:14px;opacity:0;transition:opacity 0.3s;z-index:9999;pointer-events:none';
      indicator.textContent = '下拉刷新';
      document.body.appendChild(indicator);
      return indicator;
    }
  };

  // ==================== Long Press ====================

  HTF.longPress = {
    duration: 500, // ms to trigger long press

    init: function(element, callback) {
      var timer;
      var cancelled = false;

      element.addEventListener('touchstart', function(e) {
        cancelled = false;
        timer = setTimeout(function() {
          if (!cancelled) {
            callback(e);
            navigator.vibrate && navigator.vibrate(50); // Haptic feedback
          }
        }, HTF.longPress.duration);
      });

      element.addEventListener('touchend', function() {
        cancelled = true;
        clearTimeout(timer);
      });

      element.addEventListener('touchmove', function() {
        cancelled = true;
        clearTimeout(timer);
      });
    }
  };

  // ==================== Touch-friendly Buttons ====================

  HTF.touchButton = {
    minSize: 44, // 44x44px minimum touch target (Apple HIG)

    init: function(selector) {
      selector = selector || 'button, a, .btn, [role="button"]';
      var elements = document.querySelectorAll(selector);

      elements.forEach(function(el) {
        var rect = el.getBoundingClientRect();
        if (rect.width < HTF.touchButton.minSize || rect.height < HTF.touchButton.minSize) {
          el.style.minWidth = HTF.touchButton.minSize + 'px';
          el.style.minHeight = HTF.touchButton.minSize + 'px';
        }
      });
    }
  };

  // ==================== Prevent Double Tap Zoom ====================

  HTF.preventDoubleTapZoom = {
    enabled: false,

    init: function(selector) {
      if (this.enabled || !HTF.touch.enabled) return;
      this.enabled = true;

      var elements = selector ? document.querySelectorAll(selector) : [document];

      elements.forEach(function(el) {
        var lastTap = 0;

        el.addEventListener('touchend', function(e) {
          var now = Date.now();
          if (now - lastTap < 300) {
            e.preventDefault();
          }
          lastTap = now;
        });
      });
    }
  };

  // ==================== Smooth Scroll ====================

  HTF.smoothScroll = {
    init: function(selector) {
      selector = selector || 'a[href^="#"]';
      var links = document.querySelectorAll(selector);

      links.forEach(function(link) {
        link.addEventListener('click', function(e) {
          var targetId = this.getAttribute('href').substring(1);
          if (!targetId) return;

          var target = document.getElementById(targetId);
          if (!target) return;

          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });

          // Update URL without jumping
          if (window.history.pushState) {
            window.history.pushState(null, null, '#' + targetId);
          }
        });
      });
    }
  };

  // ==================== Sticky Header ====================

  HTF.stickyHeader = {
    enabled: false,

    init: function(headerSelector) {
      if (this.enabled) return;
      this.enabled = true;

      var header = document.querySelector(headerSelector || '.site-header');
      if (!header) return;

      var lastScroll = 0;
      var delta = 5;
      var headerHeight = header.offsetHeight;

      window.addEventListener('scroll', HTF.throttle(function() {
        var currentScroll = window.pageYOffset;

        if (Math.abs(lastScroll - currentScroll) <= delta) return;

        if (currentScroll > lastScroll && currentScroll > headerHeight) {
          // Scrolling down - hide header
          header.style.transform = 'translateY(-100%)';
        } else {
          // Scrolling up - show header
          header.style.transform = 'translateY(0)';
        }

        lastScroll = currentScroll;
      }, 100));

      header.style.transition = 'transform 0.3s ease';
    }
  };

  // ==================== Image Zoom (Pinch) ====================

  HTF.imageZoom = {
    init: function(selector) {
      selector = selector || '.post-body img, .post-item img';
      var images = document.querySelectorAll(selector);

      images.forEach(function(img) {
        img.addEventListener('click', function() {
          HTF.imageZoom.showOverlay(this.src);
        });

        // Pinch zoom would require more complex gesture handling
        // For simplicity, we use click to show full-screen overlay
      });
    },

    showOverlay: function(src) {
      var overlay = document.createElement('div');
      overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.9);z-index:10000;display:flex;align-items:center;justify-content:center;cursor:zoom-out';

      var img = document.createElement('img');
      img.src = src;
      img.style.cssText = 'max-width:90%;max-height:90%;object-fit:contain';

      overlay.appendChild(img);
      document.body.appendChild(overlay);

      overlay.addEventListener('click', function() {
        document.body.removeChild(overlay);
      });

      // Swipe down to close
      HTF.swipe.init(overlay, {
        ondown: function() {
          document.body.removeChild(overlay);
        }
      });
    }
  };

  // ==================== Virtual Keyboard Helper ====================

  HTF.keyboard = {
    init: function() {
      if (!HTF.touch.enabled) return;

      var inputs = document.querySelectorAll('input, textarea');

      inputs.forEach(function(input) {
        input.addEventListener('focus', function() {
          // Scroll input into view when keyboard appears
          setTimeout(function() {
            input.scrollIntoView({behavior: 'smooth', block: 'center'});
          }, 300);
        });
      });
    }
  };

  // ==================== Haptic Feedback ====================

  HTF.haptic = {
    vibrate: function(pattern) {
      if (navigator.vibrate) {
        navigator.vibrate(pattern);
      }
    },

    light: function() {
      this.vibrate(10);
    },

    medium: function() {
      this.vibrate(20);
    },

    heavy: function() {
      this.vibrate(50);
    },

    success: function() {
      this.vibrate([10, 50, 10]);
    },

    error: function() {
      this.vibrate([20, 50, 20, 50, 20]);
    }
  };

  // ==================== Auto Initialize ====================

  HTF.ready(function() {
    if (HTF.touch.enabled) {
      // Auto-enable mobile optimizations
      HTF.swipeBack.init();
      HTF.smoothScroll.init();
      HTF.keyboard.init();
      HTF.imageZoom.init();

      // Optional: uncomment to enable
      // HTF.pullToRefresh.init();
      // HTF.stickyHeader.init();
      // HTF.preventDoubleTapZoom.init();
    }
  });

  // ==================== Export ====================

  window.HTF = HTF;

})(window, document);

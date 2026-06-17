<?php
/**
 * Responsive HTML5 Header Template
 * Compatible with IE11+ and all modern browsers
 */

$headif = array('hd_nlg1' =>'<!--','hd_nlg2' =>'-->','hd_ma1' =>'<!--','hd_ma2' =>'-->','hd_lg1' =>'<!--','hd_lg2' =>'-->');

if (empty($skin)) $skin=$db_defaultstyle;
if(file_exists("style/$skin.php") && strpos($skin,'..')===false){
	@include ("style/$skin.php");
}else{
	@include ("style/htf.php");
}
$yeyestyle=='no' ? $i_table="bgcolor=$tablecolor" : $i_table='class=i_table';

if($groupid=='guest' || !isset($groupid)){
	$headif['hd_nlg1']=$headif['hd_nlg2']='';
	if($db_regpopup=='1' && !strpos($REQUEST_URI,'register') && !strpos($REQUEST_URI,'login')){
		$head_pop='head_pop';
	}
}
else{
	$gotnewmsg>0 ? $head_gotmsg='<font color=red>有新消息</font>':$head_gotmsg='短消息';
	$headif['hd_lg1']=$headif['hd_lg2']='';
	if($htfid==$manager){$headif['hd_ma1']=$headif['hd_ma2']='';}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="<?php echo $db_charset; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo $db_bbsname; ?></title>
<meta name="keywords" content="<?php echo isset($page_keywords) ? $page_keywords : $db_bbsname; ?>">
<meta name="description" content="<?php echo isset($page_description) ? $page_description : $db_bbsname; ?>">
<meta name="generator" content="HotTextForum <?php echo $db_version; ?>">

<!-- Security Headers -->
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta name="referrer" content="same-origin">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo $imgpath; ?>/favicon.ico">
<link rel="apple-touch-icon" href="<?php echo $imgpath; ?>/apple-touch-icon.png">

<!-- Responsive CSS Framework -->
<link rel="stylesheet" href="style/responsive.css">

<!-- Legacy Browser Support (IE11) -->
<!--[if lt IE 12]>
<script src="style/polyfills.js"></script>
<style>
  /* IE11 specific fixes */
  .row { display: -ms-flexbox; }
  .col { -ms-flex: 1; }
</style>
<![endif]-->

<!-- Custom Theme CSS (if exists) -->
<?php if(file_exists("style/$skin.css")): ?>
<link rel="stylesheet" href="style/<?php echo htmlspecialchars($skin, ENT_QUOTES, 'UTF-8'); ?>.css">
<?php endif; ?>

<style>
/* Inline critical CSS for faster rendering */
body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.site-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  padding: 1rem 0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.site-header a {
  color: #fff;
  text-decoration: none;
}

.site-header .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.site-logo {
  font-size: 1.5rem;
  font-weight: 700;
  margin-right: 2rem;
}

.site-nav {
  display: flex;
  gap: 1.5rem;
  align-items: center;
  flex-wrap: wrap;
}

.site-nav a {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background 0.15s;
}

.site-nav a:hover {
  background: rgba(255,255,255,0.2);
}

.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  color: #fff;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0.5rem;
}

@media (max-width: 767px) {
  .mobile-menu-toggle {
    display: block;
  }

  .site-nav {
    display: none;
    width: 100%;
    flex-direction: column;
    margin-top: 1rem;
    gap: 0;
  }

  .site-nav.active {
    display: flex;
  }

  .site-nav a {
    width: 100%;
    text-align: left;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
}

.main-content {
  flex: 1;
  padding: 2rem 0;
}

.site-footer {
  background: #f5f5f5;
  padding: 2rem 0;
  text-align: center;
  color: #595959;
  border-top: 1px solid #d9d9d9;
  margin-top: auto;
}
</style>

<?php if(isset($head_pop)): ?>
<script>
// Login/Register popup (only for non-logged users)
window.addEventListener('DOMContentLoaded', function() {
  if (!localStorage.getItem('htf_popup_shown')) {
    setTimeout(function() {
      if (confirm('欢迎访问 <?php echo addslashes($db_bbsname); ?>！\n\n是否现在注册或登录？')) {
        window.location.href = 'register.php';
      }
      localStorage.setItem('htf_popup_shown', '1');
    }, 2000);
  }
});
</script>
<?php endif; ?>

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  var toggle = document.getElementById('mobile-menu-toggle');
  var nav = document.getElementById('site-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function() {
      nav.classList.toggle('active');
      this.setAttribute('aria-expanded', nav.classList.contains('active'));
    });
  }

  // Close mobile menu when clicking outside
  document.addEventListener('click', function(e) {
    if (nav && toggle && !nav.contains(e.target) && !toggle.contains(e.target)) {
      nav.classList.remove('active');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
});

// XSS Protection - sanitize user input
function sanitizeHTML(str) {
  var temp = document.createElement('div');
  temp.textContent = str;
  return temp.innerHTML;
}

// CSRF Token for forms (if security.php is loaded)
<?php if(function_exists('generate_csrf_token')): ?>
var csrfToken = '<?php echo generate_csrf_token(); ?>';
document.addEventListener('DOMContentLoaded', function() {
  // Auto-inject CSRF token into all forms
  var forms = document.querySelectorAll('form[method="post"], form[method="POST"]');
  forms.forEach(function(form) {
    if (!form.querySelector('input[name="csrf_token"]')) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'csrf_token';
      input.value = csrfToken;
      form.appendChild(input);
    }
  });
});
<?php endif; ?>
</script>
</head>
<body class="<?php echo $groupid; ?>-user <?php echo isset($body_class) ? $body_class : ''; ?>">

<!-- Skip to main content (accessibility) -->
<a href="#main-content" class="skip-link" style="position:absolute;left:-9999px;top:0;z-index:9999">跳转到主内容</a>

<!-- Site Header -->
<header class="site-header" role="banner">
  <div class="container">
    <div class="site-logo">
      <a href="index.php" title="返回首页"><?php echo htmlspecialchars($db_bbsname, ENT_QUOTES, 'UTF-8'); ?></a>
    </div>

    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="菜单" aria-expanded="false">
      ☰
    </button>

    <nav id="site-nav" class="site-nav" role="navigation" aria-label="主导航">
      <a href="index.php">首页</a>
      <a href="bbsfaq.php">帮助</a>
      <a href="search.php">搜索</a>

      <?php echo $headif['hd_nlg1']; ?>
      <!-- Not logged in -->
      <a href="register.php" class="btn btn-sm btn-secondary">注册</a>
      <a href="login.php" class="btn btn-sm btn-primary">登录</a>
      <?php echo $headif['hd_nlg2']; ?>

      <?php echo $headif['hd_lg1']; ?>
      <!-- Logged in -->
      <a href="usercp.php">个人中心</a>
      <a href="sendmsg.php"><?php echo $head_gotmsg; ?></a>
      <a href="login.php?action=quit">退出</a>
      <?php echo $headif['hd_lg2']; ?>

      <?php echo $headif['hd_ma1']; ?>
      <!-- Admin only -->
      <a href="admin.php" style="color:#ffeb3b">管理后台</a>
      <?php echo $headif['hd_ma2']; ?>
    </nav>
  </div>
</header>

<!-- Breadcrumb Navigation (optional) -->
<?php if(isset($breadcrumb) && !empty($breadcrumb)): ?>
<nav class="breadcrumb-nav" aria-label="面包屑导航">
  <div class="container">
    <ol class="breadcrumb" style="display:flex;list-style:none;padding:0.75rem 0;margin:0;gap:0.5rem;font-size:0.875rem">
      <li><a href="index.php">首页</a></li>
      <?php foreach($breadcrumb as $item): ?>
      <li style="color:#999">/</li>
      <li>
        <?php if(isset($item['url'])): ?>
        <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></a>
        <?php else: ?>
        <span><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ol>
  </div>
</nav>
<?php endif; ?>

<!-- Main Content -->
<main id="main-content" class="main-content" role="main">
  <div class="container">

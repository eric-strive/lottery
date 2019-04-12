<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:38:"./themes/default/mobile/login/reg.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:59:"/var/www/oscshop2/themes/default/mobile/public/top-nav.html";i:1555075681;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv=Content-Type content="text/html;charset=utf-8" />
    <title><?php echo (isset($SEO['title']) && ($SEO['title'] !== '')?$SEO['title']:''); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">    
    
    <script src="/public/static/js/jquery/jquery-1.10.2.min.js"></script>   
      
    
<link href="/public/static/view_res/mobile/css/login.css" type="text/css" rel="Stylesheet" />
<link href="/public/static/jquery-weui/dist/lib/weui.min.css" type="text/css" rel="Stylesheet" />
<link href="/public/static/jquery-weui/dist/css/jquery-weui.min.css" type="text/css" rel="Stylesheet" />
<script src="/public/static/jquery-weui/dist/js/jquery-weui.min.js"></script>
    
</head>
<body>

<div class="listTopcaption">
<div class="holder">
    <a class="listTopArrow"  onclick="history.go(-1)"></a>
    <a class="listTopArrow home"  href="<?php echo url('/mobile'); ?>"></a>
    <p><?php echo $top_title; ?></p>
</div>
</div>
<div class="TopcaptionPos"></div>


<div id="container">
	<div class="register">
	
	    <div class="weui_cells_title">用户注册</div>
	    <div class="weui_cells weui_cells_form">
	    	
	        <div class="weui_cell">
	            <div class="weui_cell_hd">
	                <label for="" class="weui_label">用户名</label>
	            </div>
	            <div class="weui_cell_bd weui_cell_primary">
	                <input name="username" class="weui_input" required="" placeholder="请输入用户名" type="text">
	            </div>            
	        </div>
	   
	        <div class="weui_cell">
	            <div class="weui_cell_hd">
	                <label for="" class="weui_label">密码</label>
	            </div>
	            <div class="weui_cell_bd weui_cell_primary">
	                <input name="password" class="weui_input" required="" pattern="[\w]{6,}" notmatchtips="密码至少6位" placeholder="请输入密码" type="password">
	            </div>            
	        </div>
	        <div class="weui_cell">
	            <div class="weui_cell_hd">
	                <label for="" class="weui_label">密码确认</label>
	            </div>
	            <div class="weui_cell_bd weui_cell_primary">
	                <input name="pwd2" class="weui_input" required="" pattern="[\w]{6,}" notmatchtips="密码至少6位" placeholder="请再次输入密码" type="password">
	            </div>            
	        </div>
	        <?php if(1 == config('use_captcha')): ?>	
	        <div class="weui_cell weui_vcode">
			<div class="weui_cell_hd">
				<label class="weui_label">验证码</label>
			</div>
			<div class="weui_cell_bd weui_cell_primary">
				<input name="captcha" class="weui_input" type="text" placeholder="请输入验证码">
			</div>
			<div class="weui_cell_ft">
				<img class="verifyimg reloadverify" src="<?php echo url('login/verify'); ?>">
			</div>
			</div>
			<?php endif; ?>
	    </div>
	    <p class="weui_cells_tips"><a href="<?php echo url('login/login'); ?>">已有账号？去登录</a></p>
	
	    <div class="weui_btn_area">
	        <a class="weui_btn weui_btn_primary js_btn">注册</a>
	    </div>
	
	</div>
</div>




</body>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?32e3cb4cf7e5c6772c4dc1f5d496b6af";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>



<script>

<?php if(1==config('use_captcha')){ ?>
	//刷新验证码
	var verifyimg = $(".verifyimg").attr("src");
    $(".reloadverify").click(function(){
        if( verifyimg.indexOf('?')>0){
            $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
        }else{
            $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
        }
    });	
<?php } ?>

$('.js_btn').click(function(){
	$.post(
		"<?php echo url('login/reg'); ?>",
		$('input[type=\'text\'],input[type=\'password\']'),
		function(d){
			if(d.error){			
				$.toast(d.error,"forbidden");			
			}else if(d.success){				
				$.toast(d.success);				
				if(d.url){
					setTimeout(function (){
				        location.href=d.url;
				    }, 1000);					
				}				
			}
		}
	);	
});
</script>

</html>
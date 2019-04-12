<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"./themes/default/mobile/agent/apply_agent.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:59:"/var/www/oscshop2/themes/default/mobile/public/top-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/wshop_companyreg.css" type="text/css" rel="Stylesheet" />
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

   
<?php if(isset($apply_result)): if($apply_result['status'] == 2): ?>
	<div style="margin:5px 0;padding:0px 8px; line-height: 20px;color:red;">
	由于您提交的资料中,<?php echo $apply_result['answer']; ?>，审核没有通过，请修改后在提交申请。
	</div>	
	<?php endif; endif; ?>
<!-- from -->
<form id="login-wrap">
    <header class="Thead">手机号</header>
    
    <?php if(isset($apply_result)): ?>
    	<input type="hidden" name="aa_id" value="<?php echo $apply_result['aa_id']; ?>" />
    <?php endif; ?>
    
    <div style="padding:0 10px;">
        <div class="gs-text">
            <input type="tel" name="tel" value="<?php echo (isset($apply_result['tel']) && ($apply_result['tel'] !== '')?$apply_result['tel']:''); ?>" tabindex="1" placeholder="手机号" />
        </div>
    </div>

    <header class="Thead">真实姓名</header>
    <div style="margin:0 10px;">
        <div class="gs-text">
            <input type="text" name="name" value="<?php echo (isset($apply_result['name']) && ($apply_result['name'] !== '')?$apply_result['name']:''); ?>"  tabindex="2" placeholder="真实姓名" />
        </div>
    </div>

    <header class="Thead">电子邮箱</header>
    <div style="padding:0 10px;">
        <div class="gs-text">
            <input type="text" name="email" value="<?php echo (isset($apply_result['email']) && ($apply_result['email'] !== '')?$apply_result['email']:''); ?>"  tabindex="3" placeholder="电子邮箱" />
        </div>
    </div>

    <header class="Thead">身份证号</header>
    <div style="padding:0 10px;">
        <div class="gs-text">
            <input type="text" name="id_cart" value="<?php echo (isset($apply_result['id_cart']) && ($apply_result['id_cart'] !== '')?$apply_result['id_cart']:''); ?>" tabindex="4" placeholder="身份证号" />
        </div>
    </div>
</form>

<a class="button green" href="javascript:;" id="reg-btn">提交申请</a>




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
$('#reg-btn').click(function(){
	$.post(
		
		<?php if(isset($apply_result)){ ?>
			'<?php echo url("Agent/edit_apply_agent"); ?>',
		<?php }else{ ?>
			'<?php echo url("Agent/apply_agent"); ?>',	
		<?php } ?>
		
		$('input[type="tel"],input[type="text"],input[type="hidden"]'),
		function(d){
			if(d.error){
				$.toast(d.error, "forbidden");
			}else if(d.success){
				$.toast(d.success);
				
				setTimeout(function (){
			      history.go(-1);
			    },2000);
			}
		}
	);
});
</script>

</html>
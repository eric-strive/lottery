<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:39:"./themes/default/mobile/user/index.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:62:"/var/www/oscshop2/themes/default/mobile/public/footer-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/uc.css" type="text/css" rel="Stylesheet" />

    
</head>
<body>



<div id="wrapper">
    <div class="close"></div>
</div>

<div class="uc-headwrap" style='background-image: url(/public/static/view_res/mobile/images/ucbag/bag2.jpg);'>
    <div class="uc-head">
    	<?php if(in_wechat()): ?>
	        <a class="headwrap"><img src="<?php echo $userinfo['userpic']; ?>/0"/></a>	             
        <?php else: ?>
	        <a class="headwrap">
	        	<?php if(!empty($userinfo['userpic'])): ?>
	        	<img src="<?php echo $userinfo['userpic']; ?>"/>
	        	<?php else: ?>
	        	<img src="/public/static/view_res/mobile/images/icon/iconfont-iconname_2x.png"/>
	        	<?php endif; ?>
	        </a>  
        <?php endif; ?>
        <span class="uc-name"><?php echo $userinfo['nickname']; ?></span>   
    </div>
    <div class="comspreadstat clearfix">
        <span class="spread-item" onclick="location.href = '<?php echo url("Points/points_list"); ?>';"><b><?php echo $userinfo['points']; ?></b>积分</span>        
        <span class="spread-item" onclick="location.href = '<?php echo url("User/wish_list"); ?>';"><b><?php echo $userinfo['wish']; ?></b>收藏</span>
    </div>
</div>

<div class="uc-section" onclick="location.href = '<?php echo url("order/index"); ?>';"><i class='dingdan'></i><b>查看全部</b>我的订单</div>

<div class='uc-order-sec clearfix'>
    <a class='uc-order-btn fukuan' href="<?php echo url('order/index',array('status'=>config('default_order_status_id'))); ?>"><i></i>待付款<b class='prices'><?php echo (isset($no_pay) && ($no_pay !== '')?$no_pay:'0'); ?></b></a>
    <a class='uc-order-btn fahuo' href="<?php echo url('order/index',array('status'=>config('paid_order_status_id'))); ?>"><i></i>待发货<b class='prices'><?php echo (isset($paid) && ($paid !== '')?$paid:'0'); ?></b></a>    
</div>

<div class="uc-section" onclick="location.href = '<?php echo url("user/wish_list"); ?>';">
    <i></i><b>我喜欢，我收藏</b>我的收藏
</div>

<div class="uc-section" onclick="location.href = '<?php echo url("points/index"); ?>'">
    <i class='credit'></i><b>您有 <?php echo $userinfo['points']-$userinfo['cash_points']; ?> 积分可兑换</b>积分兑换
</div>	
	<?php if($userinfo['is_agent'] == 1): ?>	
	        <div class="uc-section" onclick="location.href = '<?php echo url("Agent/sub_agent"); ?>'">
	            <i class='hezuo'></i><b>总收益： &yen; <?php echo $userinfo['total_bonus']; ?> </b>我的代理
	        </div>
	        <div class="uc-section" onclick="location.href = '<?php echo url("Agent/my_agent_info"); ?>'">
	            <i class='infoedit'></i><b>可查看/修改资料</b>我的资料
	        </div>
	        <div class="uc-section" id="companyQrcode">
	            <i class='qrcode'></i><b>一起来推广吧</b>推广分享
	        </div>
	        <div style="height:100px;"></div>	        
	<?php else: ?>
	    <div class="uc-section" onclick="location.href = '<?php echo url("Agent/apply_agent"); ?>'">
	        <i class='hezuo'></i><b>加入代理，共同成长</b>成为代理
	    </div>
	<?php endif; if(in_wechat()): endif; ?>



<footer id="footer">
<div class="bottom_nav"><a class="nav_index <?php if(isset($flag) AND ($flag == 'index')): ?> hover<?php endif; ?>" href="<?php echo url('/mobile'); ?>"><i></i>购物</a>
<a class="nav_search <?php if(isset($flag) AND ($flag == 'search')): ?> hover<?php endif; ?>" href="<?php echo url('category/index'); ?>"><i></i>搜索</a>	
<a class="nav_shopcart" href="<?php echo url('cart/index'); ?>"><i></i>购物车</a>	
<a class="nav_me <?php if(isset($flag) AND ($flag == 'user')): ?> hover<?php endif; ?>" href="<?php echo url('user/index'); ?>"><i></i>个人中心</a></div>		
</footer>

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



<script data-main="/public/static/view_res/mobile/js/shop_uchome.js" src="/public/static/view_res/mobile/js/require.min.js"></script>

</html>
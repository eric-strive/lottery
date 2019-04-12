<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:47:"./themes/default/mobile/points/points_list.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:59:"/var/www/oscshop2/themes/default/mobile/public/top-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/wshop_company_center.css" type="text/css" rel="Stylesheet" />

    
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


<div class="comspreadstat clearfix">
	<span class="spread-item" style="width:50%;">总积分<b><?php echo (isset($user_info['points']) && ($user_info['points'] !== '')?$user_info['points']:'0'); ?></b></span>
	<span class="spread-item" style="width:50%;">已兑换<b><?php echo (isset($user_info['cash_points']) && ($user_info['cash_points'] !== '')?$user_info['cash_points']:'0'); ?></b></span>
</div>
<header class="Thead">积分记录</header>
<div id="ulist">            
    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>            
        <section class="ulist clearfix">
            <div><?php echo $p['description']; if(isset($p['order_id'])){ ?><a style="color:red;margin:0 6px;" href='<?php echo url("order/order_info",array("order_id"=>$p["order_id"])); ?>'><?php echo $p['order_num_alias']; ?></a><?php } if($p['prefix']==1){echo '+';}else{echo '-';} ?><?php echo $p['points'].' '.date('Y-m-d',$p['creat_time']); ?> </div>                     
        </section>
    <?php endforeach; endif; else: echo "$empty" ;endif; ?>
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



</html>
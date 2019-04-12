<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:40:"./themes/default/mobile/index/index.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:58:"/var/www/oscshop2/themes/default/mobile/public/search.html";i:1555075681;s:62:"/var/www/oscshop2/themes/default/mobile/public/footer-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/home.css" type="text/css" rel="Stylesheet" />
<script src="/public/static/artTemplate/template.js"></script>
<script src="/public/static/view_res/mobile/load_list.js"></script>
<script src="/public/static/view_res/mobile/index.js"></script>
    
</head>
<body>

<form class="search-w-box" onsubmit='searchdo(this);return false;'>
	<input type="search" name="search" id="searchBox" targ="<?php echo url('search/index'); ?>" value="<?php echo input('param.search'); ?>"  class="search-w-input" placeholder="搜一搜，找到你想要的" /> 
</form>
<script>
function searchdo(form) {
    var search = $('input[type=search]', form);
    if (search.val() === '')
    return;            
 	var url = search.attr('targ');
 	
    var query = $('.search-w-box').find('input,select').serialize();
    query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
    query = query.replace(/^&/g, '');
    if (url.indexOf('?') > 0) {
        url += '&' + query;
    } else {
        url += '?' + query;
    }
    window.location.href = url;            
}	
</script>


<section class="home-recom">
    <section class="clearfix" id="goods_list">

    </section>
    <div id="list-loading" style="display: none;"><img src="/public/static/view_res/mobile/images/icon/spinner-g-60.png" width="30"></div>
</section>
<script id="list" type="text/html">
    	<@each list as value i@>
	        <a href="/mobile/goods/detail/id/<@value.goods_id@>" class="hplist">
	                <img src="/<@value.image@>" />
	                <p><@value.name@></p>
	        <i>&yen;<@value.price@></i>
	        </a>
        <@/each@>
</script>


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



<?php if(in_wechat()): ?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>    
<script type="text/javascript">
wx.config({
    debug: false,            
    appId: '<?php echo $signPackage['appId']; ?>',
    timestamp: <?php echo $signPackage['timestamp']; ?>,
    nonceStr: '<?php echo $signPackage['nonceStr']; ?>',
    signature: '<?php echo $signPackage['signature']; ?>',            
    jsApiList: [
    'checkJsApi',
    'onMenuShareTimeline',
    'onMenuShareAppMessage'
    ]
});	
        
wx.ready(function(){
	
		var is_agent = '<?php echo user("is_agent"); ?>';
        var site_url='<?php echo request()->domain(); ?>';
        if(is_agent==1){        	
        	var url =site_url+'/mobile/index/agent_share/osc_aid/'+'<?php echo hashids()->encode(user("uid")); ?>';
        }else{
        	var url =site_url+'/mobile/';
        }           
   
        //获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
        wx.onMenuShareTimeline({
            title: '<?php echo config("SITE_NAME"); ?>',
            link: url, 
            imgUrl: site_url+'/uploads/<?php echo config("SITE_ICON"); ?>',
            success: function () {
               pvShareCallback(url,'分享到朋友圈');
            }
        });
        //获取“分享给朋友”按钮点击状态及自定义分享内容接口
        wx.onMenuShareAppMessage({
            title: '<?php echo config("SITE_NAME"); ?>',
            desc: '<?php echo config("SITE_NAME"); ?>',
            link: url,
            imgUrl: site_url+'/uploads/<?php echo config("SITE_ICON"); ?>',
            success: function () {
              pvShareCallback(url,'分享给朋友');
            }
        });
        function pvShareCallback(url,type) {
        	
            $.post("<?php echo url('Index/add_share'); ?>", {
                url: url,
                uid:'<?php echo user("uid"); ?>',
                type:type                    
            });
        }  
        
});	        
         	        
</script> 
<?php endif; ?>
<script>
$(function(){
scroll_load('mobile/index/ajax_goods_list','list','goods_list');
});
</script>

</html>
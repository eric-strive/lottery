<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:41:"./themes/default/mobile/goods/detail.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:59:"/var/www/oscshop2/themes/default/mobile/public/top-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/product.css" type="text/css" rel="Stylesheet" />
<link href="/public/static/jquery-weui/dist/lib/weui.min.css" type="text/css" rel="Stylesheet" />
<link href="/public/static/jquery-weui/dist/css/jquery-weui.min.css" type="text/css" rel="Stylesheet" />
<script src="/public/static/jquery-weui/dist/js/jquery-weui.min.js"></script>
<script src="/public/static/jquery-weui/dist/js/swiper.min.js"></script>

<style>
	.swiper-container {
	    width: 100%;
	}
	.swiper-container img {
	    display: block;
	    width: 100%;
	}
	.option_name{margin-left:5px;}
	      
	/*重载jquery-weui.min.css中样式*/  
	label > * {
	  pointer-events: auto;
	}	
</style>
    
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



<div class="swiper-container">
      <!-- Additional required wrapper -->
      <div class="swiper-wrapper">
        <!-- Slides -->
        <?php if(is_array($image) || $image instanceof \think\Collection || $image instanceof \think\Paginator): $i = 0; $__LIST__ = $image;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$image): $mod = ($i % 2 );++$i;?>            
            <div class="swiper-slide"><img src="/<?php echo resize($image['image'],400,400); ?>" /></div>
      	<?php endforeach; endif; else: echo "" ;endif; ?>        
      </div>
      <!-- If we need pagination -->
      <div class="swiper-pagination"></div>
</div>

<div id="container">
<a class="uc-add-like">收藏</a>

	<input type="hidden" value="<?php echo $goods['minimum']; ?>" name="quantity">
	<input id="goods_id" type="hidden" value="<?php echo $goods['goods_id']; ?>" name="goods_id">
	
	<p class="vpd-title" style='height:auto;'>
           <?php echo $goods['name']; ?>
    </p>
    <?php if(isset($points_goods) AND ($points_goods == 'points')): ?>
    	<input type="hidden" value="points" name="pay_type">
	    <dl class="pd-dsc clearfix">
	        <dt>积分：</dt>
	        <dt class="prices" data-price="<?php echo round($goods['pay_points'],2); ?>" id="pd-market-price"><?php echo round($goods['pay_points'],2); ?></dt>
	   	</dl>
   	<?php else: ?>
	   	<dl class="pd-dsc clearfix">
	        <dt>零售价：</dt>
	        <dt class="prices" data-price="<?php echo round($goods['price'],2); ?>" id="pd-market-price">&yen;<?php echo round($goods['price'],2); ?></dt>
	   	</dl>
   	<?php endif; ?>
   	<dl class="pd-dsc clearfix" id="product_stock_wrap">
        <dt>库存量：</dt>
        <dt id="pd-stock"><?php echo $goods['quantity']; ?></dt>
    </dl>
    
    <?php if(!empty($discount)): ?>
    <dl class="pd-dsc clearfix" id="product_stock_wrap">
            <dt>批发价：</dt>
            <?php if(is_array($discount) || $discount instanceof \think\Collection || $discount instanceof \think\Paginator): $i = 0; $__LIST__ = $discount;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            	<br />
            	<dt id="pd-stock"> <?php echo $v['quantity']; ?><?php echo $goods['sku']; ?> 或更多 &yen; <?php echo $v['price']; ?></dt>
            <?php endforeach; endif; else: echo "" ;endif; ?>
    </dl>
	<?php endif; if($options): ?>	
			      <div class="options">
			        <h2>可选项</h2>			        
			        <?php if(is_array($options) || $options instanceof \think\Collection || $options instanceof \think\Paginator): $i = 0; $__LIST__ = $options;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;if($option['type'] == 'select'): ?>			        	
				        <div class="boss_check">
					        <div class="box-check">	
						        <div id="option-<?php echo $option['goods_option_id']; ?>" class="option">
						          <div class="title_text"><b><?php echo $option['name']; ?>:</b>
							          <?php if($option['required'] == 1): ?>
							          	<span class="required">*</span>
							          	<?php endif; ?>
						          </div>
						          <select name="option[<?php echo $goods['goods_id']; ?>,<?php echo $option['option_id']; ?>]">
						            <option value=""> --- 请选择 --- </option>
						            <?php if(is_array($option['goods_option_value']) || $option['goods_option_value'] instanceof \think\Collection || $option['goods_option_value'] instanceof \think\Paginator): $i = 0; $__LIST__ = $option['goods_option_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option_value): $mod = ($i % 2 );++$i;?>	
							            <option title="数量<?php echo $option_value['quantity']; ?>" value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; if($option_value['goods_price'] != '0.00'): ?>		
							            		(<?php echo $option_value['price_prefix']; ?>￥<?php echo $option_value['price']; ?>)
							            	<?php endif; ?>
							            </option>
						            <?php endforeach; endif; else: echo "" ;endif; ?>
						          </select>
						        </div>
					         </div>
				         </div>
				        <?php endif; if($option['type'] == 'radio'): ?>
							<div class="boss_check">
								<div class="box-check">
							        <div id="option-<?php echo $option['goods_option_id']; ?>">
							          <p><b><?php echo $option['name']; ?>:</b>						      
								          	<?php if($option['required'] == 1): ?>
								          	<span class="required">*</span>
								          	<?php endif; ?>						          
							          </p>
							          <?php if(is_array($option['goods_option_value']) || $option['goods_option_value'] instanceof \think\Collection || $option['goods_option_value'] instanceof \think\Paginator): $i = 0; $__LIST__ = $option['goods_option_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option_value): $mod = ($i % 2 );++$i;if(!empty($option_value['image'])): ?>			
							          	<a title="数量<?php echo $option_value['quantity']; ?>" href="/uploads/<?php echo $option_value['image']; ?>">
							          		<img src="/<?php echo resize($option_value['image'],30,30); ?>" />									          										          
							          	</a>
						          	 <?php endif; ?>			          									          	
							          <label for="option-value-<?php echo $option_value['goods_option_value_id']; ?>">								          	
							          	<input  class="option" type="radio" name="option[<?php echo $goods['goods_id']; ?>,<?php echo $option['option_id']; ?>]" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['goods_option_value_id']; ?>" />									          	
								          	<span class="option_name" title="数量<?php echo $option_value['quantity']; ?>"><?php echo $option_value['name']; ?></span> 						          
								            <?php if($option_value['goods_price'] != '0.00'): ?>		
								            	(<?php echo $option_value['price_prefix']; ?>￥<?php echo $option_value['price']; ?>)
								            <?php endif; ?>								            
							          </label>
							          <br />
							          <?php endforeach; endif; else: echo "" ;endif; ?>
							        </div>
								</div>
							</div>	
					     <?php endif; if($option['type'] == 'checkbox'): ?>	
						<div class="box-check">
					        <div id="option-<?php echo $option['goods_option_id']; ?>" class="option">
					          <p><b><?php echo $option['name']; ?>:</b>
						          <?php if($option['required'] == 1): ?>
									   <span class="required">*</span>
								  <?php endif; ?>	
					          </p>		        
					          <?php if(is_array($option['goods_option_value']) || $option['goods_option_value'] instanceof \think\Collection || $option['goods_option_value'] instanceof \think\Paginator): $i = 0; $__LIST__ = $option['goods_option_value'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option_value): $mod = ($i % 2 );++$i;if(!empty($option_value['image'])): ?>			
							          	<a title="数量<?php echo $option_value['quantity']; ?>" href="/uploads/<?php echo $option_value['image']; ?>">
							          		<img src="/<?php echo resize($option_value['image'],30,30); ?>" />									          										          
							          	</a>
					          	   <?php endif; ?>
						          <input type="checkbox" name="option[<?php echo $goods['goods_id']; ?>,<?php echo $option['option_id']; ?>][]" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['goods_option_value_id']; ?>" />
						          <label for="option-value-<?php echo $option_value['goods_option_value_id']; ?>">
							           
							          <span title="数量<?php echo $option_value['quantity']; ?>"><?php echo $option_value['name']; ?></span> 						          
							           <?php if($option_value['goods_price'] != '0.00'): ?>		
							            	(<?php echo $option_value['price_prefix']; ?>￥<?php echo $option_value['price']; ?>)
							           <?php endif; ?>
						          </label>
						          <br />
					          <?php endforeach; endif; else: echo "" ;endif; ?>
					        </div>
				        </div>											       
				       <?php endif; endforeach; endif; else: echo "" ;endif; ?>
		      </div>
		      <?php endif; ?>
    
    
</div>   

<header class="Thead" id="vpd-detail-header">产品详情</header>
    <div id="vpd-content" class="notload">下拉加载详细介绍</div>   	
    
    <?php if(!isset($points_goods)): ?>
	   	<header class="Thead">随便逛逛</header>   	
	   	<?php if(isset($related_goods)): ?>
	    <div id="pd-recoment">
	        <div class='pd-box clearfix'>
	            <?php if(is_array($related_goods) || $related_goods instanceof \think\Collection || $related_goods instanceof \think\Paginator): $i = 0; $__LIST__ = $related_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?>
	                <a class="slist-item" href="<?php echo url('goods/detail',array('id'=>$d['goods_id'])); ?>">
	                    <div class='pd-box-inner'>
	                        <img src="/<?php echo resize($d['image'],120,120); ?>" alt='<?php echo $d['name']; ?>'/>
	                        <p class='Elipsis'><?php echo $d['name']; ?></p>                        
	                    </div>
	                </a>
	            <?php endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	    </div>
		<?php endif; endif; if(isset($points_goods)): ?>
	<div id="appCartWrap" class="clearfix" style="padding: 0 10px;">
		<a class="button" id="buy-button" style="width: 100%;" data-add="0" >立即兑换</a>  
	</div>
<?php else: ?>
<div id="appCartWrap" class="clearfix">    
	<a class="button" id="addcart-button"  data-add="1">加入购物车</a>   
	<a class="button" id="buy-button" data-add="0" >立即购买</a>  
	<a id="toCart" href="<?php echo url('cart/index'); ?>"><i>
		<?php if(session('mobile_total')): ?>
			<?php echo \think\Session::get('mobile_total'); else: ?>
			0
		<?php endif; ?>
		</i></a>
</div>
<?php endif; ?>


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
$(function(){
	
	$(".swiper-container").swiper({
        loop: true,
        autoplay: 3000
      });
    
    /**
     * 商品介绍是否已经加载标记
     * @type Boolean
     */
    var contentLoaded = false;
    /**
     *加载商品详情
     */
    $(window).scroll(function () {
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) - 5;
        if ($(window).height() <= totalheight && !contentLoaded) {
            $('#vpd-content').html('');
            contentLoaded = true;
            
            $.showLoading();
            // ajax 加载商品详情
            $.ajax({
                url: "<?php echo url('goods/get_description',array('id'=>$goods['goods_id'])); ?>",
              	dataType: 'html',
                success: function (data) {
    
                    $('#vpd-content').html(data);
                    $('#vpd-detail-header').show();
                    $('.notload').removeClass('notload');
                    $('#vpd-content').fadeIn();
                    // 调整图片
                    $('#vpd-content img').each(function () {
                        $(this).on('load', function () {
                            if ($(this).width() >= document.body.clientWidth) {
                                $(this).css('display', 'block');
                            }
                            $(this).height('auto');
                        });
                    });
                    $('#vpd-content').find('div').width('auto');
                },
                error: function () {
                    
                }
            });
			setTimeout(function (){
		        $.hideLoading();		     
		    }, 500);
        }
    });
    /**
     *加入购物车
     */
    $('#buy-button,#addcart-button').bind('click', function() {		
    	
		var type=$(this).attr('data-add');			
			
		$.ajax({
				url: "<?php echo url('cart/add'); ?>",
				type: 'post',
				data: $('#container input[type=\'hidden\'],#container input[type=\'radio\']:checked,#container input[type=\'checkbox\']:checked,#container select'),
				
				dataType: 'json',
				success: function(json) {
					$('.box-check').removeClass('text-error');
					$('.text-danger').remove();
					if (json.error) {
					
				       if (json['goods_option_id']) {
				         
				         var id=json['goods_option_id'];
				         
				            var element = $('#option-'+id);
				
				            if (element.parent().hasClass('box-check')) {
				              element.parent().addClass('text-error').after('<div class="text-danger">' + json.error + '</div>');
				            } else {
				              element.after('<div class="text-danger">' + json.error+ '</div>');
				            }
				            $.toast(json.error,"forbidden");				        
						}else{						
							$.toast(json.error,"forbidden");	
						}
						if(json.url){
							setTimeout(function (){
						        location.href=json.url;
						    }, 1000);
						}
					}	
					
					if (json.success) {
						<?php if(!isset($points_goods)){ ?>
							$.toast("已加入购物车");						
							$('#toCart').find('i').text(json.total);
							
							if(type==0){							
							setTimeout(function (){
						        location = "<?php echo url('cart/index'); ?>";
						    }, 1000);							
						}
						<?php }else{ ?>
							$.showLoading();
							if(type==0){							
							setTimeout(function (){
						        location = "<?php echo url('cart/index',array('type'=>'points')); ?>";
						    }, 1000);							
						}
						<?php } ?>
						
						
					}
				
			}
		});	
	});
	 /**
     *加入收藏
     */
	$('.uc-add-like').bind('click', function() {
		
		var goods_id=$('#goods_id').val();
		$.post(
			"<?php echo url('goods/add_wish'); ?>",
			{id:goods_id},
			function(d){
				if(d){					
					if(d.error){
						$.toast(d.error,"forbidden");	
					}else if(d.success){
						$.toast(d.success);
					}					
				}
			}
		);
	}); 


});
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
        	var url =site_url+'/mobile/goods/agent_share/osc_aid/'+'<?php echo hashids()->encode(user("uid")); ?>'+'/id/'+'<?php echo input("param.id") ?>';
        }else{
        	var url = '<?php echo request()->url(true); ?>';
        }           
   
        //获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
        wx.onMenuShareTimeline({
            title: '<?php echo $goods['name']; ?>',
            link: url, 
            imgUrl: site_url+'<?php echo resize($goods['image'],100,100); ?>',
            success: function () {
               pvShareCallback(url,'分享到朋友圈');
            }
        });
        //获取“分享给朋友”按钮点击状态及自定义分享内容接口
        wx.onMenuShareAppMessage({
            title: '<?php echo $goods['name']; ?>',
            desc: '<?php echo $goods['name']; ?>',
            link: url,
            imgUrl: site_url+'/<?php echo resize($goods['image'],100,100); ?>',
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

</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:40:"./themes/default/mobile/order/index.html";i:1555075681;s:56:"/var/www/oscshop2/themes/default/mobile/public/base.html";i:1555075681;s:59:"/var/www/oscshop2/themes/default/mobile/public/top-nav.html";i:1555075681;}*/ ?>
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
      
    
<link href="/public/static/view_res/mobile/css/uc.css" type="text/css" rel="Stylesheet"/>
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


<input type="hidden" value="<?php echo input('param.status'); ?>" id="status"/>

<div class='clearfix' id='uc-order-sort-bar'>
    <div class="uc-order-sort <?php if($status==''){echo 'hover';} ?>" data-status=""><b>全部</b></div>
    <div class="uc-order-sort <?php if($status==config('default_order_status_id')){echo 'hover';} ?>" data-status="<?php echo config('default_order_status_id'); ?>"><b>待付款</b></div>
    <div class="uc-order-sort <?php if($status==config('paid_order_status_id')){echo 'hover';} ?>" data-status="<?php echo config('paid_order_status_id'); ?>"><b>待发货</b></div>	
    <div class="uc-order-sort <?php if($status==config('cancel_order_status_id')){echo 'hover';} ?>" data-status="<?php echo config('cancel_order_status_id'); ?>"><b>已取消</b></div>
    <div class="uc-order-sort <?php if($status==config('complete_order_status_id')){echo 'hover';} ?>" data-status="<?php echo config('complete_order_status_id'); ?>"><b>已完成</b></div>
</div>

<div id="uc-orderlist"></div>
<div id="list-loading" style="display: none;"><img src="/public/static/view_res/mobile/images/icon/spinner-g-60.png" width="30"></div>





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



<?php if(in_wechat()){ ?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $signPackage['appId']; ?>',
    timestamp: <?php echo $signPackage['timestamp']; ?>,
    nonceStr: '<?php echo $signPackage['nonceStr']; ?>',
    signature: '<?php echo $signPackage['signature']; ?>',
    jsApiList: ['chooseWXPay']
});	
</script>
<?php } ?>
<script>

var currentOrderpage = 0;
var orderLoading = false;
var orderLoadingLock = false;
var totalheight;

// orderlist列表页面
if ($('#uc-orderlist').length > 0) {
    // init list
    loadOrderList(currentOrderpage);
    // onscroll bottom
    $(window).scroll(function () {
        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 150;
        if ($(document).height() <= totalheight && !orderLoading) {
            //加载数据
            loadOrderList(++currentOrderpage);
        }
    });
}

$('.uc-order-sort').unbind('click').click(function () {
    currentOrderpage = -1;
    orderLoading = false;
    orderLoadingLock = false;
    $('#status').val($(this).attr('data-status'));
    $('.uc-order-sort.hover').removeClass('hover');
    $(this).addClass('hover');
    loadOrderList(currentOrderpage);
});


function loadOrderList(page) {
    if (!orderLoadingLock) {
        page = parseInt(page);
        if (page === -1) {
            page = 0;
            $("#uc-orderlist").html('');
        }
        orderLoading = true;
        $('#list-loading').show();
            $.get('<?php echo url("order/ajax_order_list"); ?>'+'/page/'+ page +'/status/'+ $('#status').val(), function (HTML) {            	
            orderLoading = false;            
            if (HTML === '' && page === 0) {
                // 什么都没有
                $("#uc-orderlist").append('<div class="emptyTip">暂无数据</div>');
            } else if (HTML !== '') {
                if (page === 0) {
                    $("#uc-orderlist").html(HTML);
                } else {
                    $("#uc-orderlist").append(HTML);
                }
            } else {
                orderLoadingLock = true;
            }
            $('#list-loading').hide();
        
        });
    }
}

function cancelOrder(orderId, node) {
        orderId = parseInt(orderId);
        if (orderId > 0) {
            if (confirm('你确认要取消订单吗?')) {
                $.post('<?php echo url("order/cancel_order"); ?>', {
                    order_id: orderId
                }, function(res) {
                    if (res == "1") {
                        $(node).parent().parent().slideUp();
                    } else {
                        alert('订单取消失败！');
                    }
                });
            }
        }
}

function alipayOrder(orderId){
	orderId = parseInt(orderId);
    if (orderId > 0) {
        if (confirm('你确认要支付吗?')) {
            $.post('<?php echo url("payment/alipay_repay"); ?>', {
                order_id: orderId
            }, function(d) {
               if(d.error){
               		$.toast(d.error,"forbidden");
               		return;
               }else if(d.success){
               		location=d.url;
               }
            });
        }
    }
}
<?php if(in_wechat()){ ?>
	
function wxpayOrder(orderId){
	
	orderId = parseInt(orderId);
    if (orderId > 0) {
      
            $.post('<?php echo url("payment/weixin_repay"); ?>', {
                order_id: orderId
            }, function(r) {
            	
               if(r.error){
               		$.toast(r.error,"forbidden");
               		return;
               }
               
               if (r.ret_code === 0) {
        
		          if (r.bizPackage.package !== 'prepay_id=') {
		                // 支付操作成功
		                r.bizPackage.success = wepayCallback;
		                // 支付操作取消
		                r.bizPackage.cancel = wepayCancel;
		                // 支付操作出错
		                r.bizPackage.fail = wepayError;
		                // 发起微信支付		                
		                wx.chooseWXPay(r.bizPackage);
		           
		            } else {
		                wepayError();
		            }
		        } else if(r.ret_code === 11){
		       
		            alert('订单创建失败！' + r.ret_msg);
		        }
               
            });
       
    }	
}

/**
 * 微信支付回调
 * @param {type} res
 * @returns {undefined}
 */
function wepayCallback(res) {
		
	 if(res.errMsg == "chooseWXPay:ok" ) {
        location.href = "<?php echo url('PaySuccess/index'); ?>";
        }else{
            alert(res.errMsg);
        }
        
}
/**
 * 微信支付失败
 */
function wepayError(res) {
    alert('微信支付发起失败');    
}      
/**
 * 微信支付手动取消
 */
function wepayCancel() {

}  
<?php } ?>
</script>

</html>
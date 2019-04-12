<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"./themes/default/mobile/order/ajax_order_list.html";i:1555075681;}*/ ?>
<?php use think\Db; if(is_array($order) || $order instanceof \think\Collection || $order instanceof \think\Paginator): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$orders): $mod = ($i % 2 );++$i;?>
    <div class="uc-orderitem" id="orderitem<?php echo $orders['order']['order_id']; ?>">
        <div class="uc-seral clearfix">
            <p class="order_serial">订单号：<?php echo $orders['order']['order_num_alias']; ?></p>

            <p class="order_status"><?php echo get_order_status_name($orders['order']['order_status_id']); ?></p>
        </div>
        <?php if(is_array($orders['list']) || $orders['list'] instanceof \think\Collection || $orders['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $orders['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?>
            <div class="clearfix items"
                 onclick="location = '<?php echo url("order/order_info",array("order_id"=>$orders["order"]["order_id"])); ?>';">
                <img class="ucoi-pic"
                     src="/<?php echo resize($o['image'],100,100); ?>">

                <div class="ucoi-con">
                    <!-- 商品标题 -->
                    <span class="title" style='height:42px;'><?php echo $o['name']; ?></span>
                    <span class="spec Elipsis">
                        						
						<?php if($option_list=Db::query('select * from '.config('database.prefix').'order_option where order_goods_id='.$o['order_goods_id'].' and order_id='.$orders["order"]["order_id"])){ foreach ($option_list as $option) { ?>
		                <br />
		                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
		                <?php } } ?>
                    </span>
                    <!-- 商品单价 -->
                    
                    
                    <?php if($orders['order']['points_order']==0){ ?>
			            <span class="price">
	                    	<span class="dprice">&yen;<?php echo $o['price']; ?></span> x 
	                    	<span class="dcount"><?php echo $o['quantity']; ?></span>
                    	</span>			           
		           <?php } ?>
                    
                </div>
            </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
         <div class="uc-summary clearfix" style='padding:8px 7px;text-align:right;'>
         	<?php if($orders['order']['points_order']==0){ ?>
            <div class="sum">
				总计: <span class="dprice">&yen;<?php echo $orders['order']['total']; ?></span>
            </div>
           <?php }elseif($orders['order']['points_order']==1){ ?>
           	<div class="sum">
				积分: <span class="dprice"><?php echo $orders['order']['pay_points']; ?></span>
            </div>
           <?php } if($orders['order']['order_status_id']==config('default_order_status_id')){ ?>             
                <a class="olbtn cancel" href="javascript:;" onclick="cancelOrder(<?php echo $orders['order']['order_id']; ?>, this);">取消订单</a>
                <a class="olbtn wepay wepay_button" href="javascript:;" <?php if(in_wechat()): ?>  onclick="wxpayOrder(<?php echo $orders['order']['order_id']; ?>);"<?php else: ?>  onclick="alipayOrder(<?php echo $orders['order']['order_id']; ?>);"<?php endif; ?> data-id="<?php echo $orders['order']['order_id']; ?>">立即支付</a>         
            <?php }elseif($orders['order']['order_status_id']==config('paid_order_status_id')){ ?> 
                <a class="olbtn wepay" href='<?php echo url("order/order_info",array("order_id"=>$orders["order"]["order_id"])); ?>'>订单详情</a>
            <?php }elseif($orders['order']['order_status_id']==config('cancel_order_status_id')){ ?>               
                <a class="olbtn cancel" href='<?php echo url("order/order_info",array("order_id"=>$orders["order"]["order_id"])); ?>'>订单详情</a>
            <?php }elseif($orders['order']['order_status_id']==config('complete_order_status_id')){ ?>               
                <a class="olbtn cancel" href='<?php echo url("order/order_info",array("order_id"=>$orders["order"]["order_id"])); ?>'>订单详情</a>                
            <?php } ?>
            
        </div> <!---->
    </div>
<?php endforeach; endif; else: echo "" ;endif; ?>
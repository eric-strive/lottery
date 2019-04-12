<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"./themes/default/mobile/goods/get_description.html";i:1555075681;}*/ ?>
<?php if(!empty($description)): if(is_array($description) || $description instanceof \think\Collection || $description instanceof \think\Paginator): $i = 0; $__LIST__ = $description;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$des): $mod = ($i % 2 );++$i;?>
		<p style="text-align:center">
			<img alt="<?php echo $des['description']; ?>" src="/<?php echo resize($des['image'],480); ?>" />
		</p>
	<?php endforeach; endif; else: echo "" ;endif; endif; ?>

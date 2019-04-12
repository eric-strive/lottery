<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:37:"./oscshop/admin/view/goods/index.html";i:1555075680;s:53:"/var/www/oscshop2/oscshop/admin/view/public/base.html";i:1555075680;}*/ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo \think\Config::get('SITE_NAME'); ?>-后台管理中心</title>

		<meta name="description" content="<?php echo \think\Config::get('SITE_NAME'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="/public/static/js/bt/bootstrap.min.css" />
		<link rel="stylesheet" href="/public/static/view_res/admin/ace/font-awesome/4.2.0/css/font-awesome.min.css" />


		<!-- ace styles -->
		<link rel="stylesheet" href="/public/static/view_res/admin/ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/public/static/view_res/admin/ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/public/static/view_res/admin/ace/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="/public/static/view_res/admin/ace/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/public/static/view_res/admin/ace/js/html5shiv.min.js"></script>
		<script src="/public/static/view_res/admin/ace/js/respond.min.js"></script>
		<![endif]-->
		<style>
			.table thead > tr > td, .table tbody > tr > td {
			    vertical-align: middle;
			}	
			.table thead td span[data-toggle="tooltip"]:after, label.control-label span:after {
				font-family: FontAwesome;
				color: #1E91CF;
				content: "\f059";
				margin-left: 4px;
			}
		</style>
		
		
			
		
		
		
		<script src="/public/static/js/jquery/jquery-2.0.3.min.js"></script>
		<script src="/public/static/js/jquery/jquery-migrate-1.2.1.min.js"></script>
			
		
		<script src="/public/static/artDialog/artDialog.js"></script>
		<link href="/public/static/artDialog/skins/default.css" rel="stylesheet" type="text/css" />			
		
		<script>
			var filemanager_url="<?php echo url('admin/FileManager/index'); ?>";
		</script>
		<script src="/public/static/js/oscshop_common.js"></script>
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="<?php echo url('admin/Index/index'); ?>" class="navbar-brand">
						<small>							
							<?php echo \think\Config::get('SITE_NAME'); ?> 后台管理
						</small>
					</a>
					<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">

						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								
								<span class="user-info">
									<?php echo session('user_auth.username'); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								
								<li>
									<a target="_blank" href="<?php echo \think\Request::instance()->root(true); ?>">网站前台</a>
								</li>
								
								<li>
									<a href="<?php echo url('admin/User/edit',array('id'=>session('user_auth.uid'))); ?>">修改密码</a>
								</li>
								
								<li><a href="<?php echo url('admin/Index/clear'); ?>">清空缓存</a></li>

								<li>
									<a href="<?php echo url('admin/Index/logout'); ?>">退出系统</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>

			
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
			
			<div id="sidebar" class="sidebar h-sidebar navbar-collapse collapse">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>
				
				<ul class="nav nav-list">
					<li class="hover">
						<a target="_blank" href="<?php echo \think\Request::instance()->root(true); ?>">
							<i class="menu-icon fa fa fa-home fa-lg"></i>
							<span class="menu-text">前台 </span>
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>
					</li>
					
					<?php if(is_array($admin_menu) || $admin_menu instanceof \think\Collection || $admin_menu instanceof \think\Paginator): $i = 0; $__LIST__ = $admin_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?>					
					<li class="hover <?php if(isset($breadcrumb1) AND ($breadcrumb1 == $m["title"])): ?> active open <?php endif; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa <?php echo $m['icon']; ?>"></i>
							<span class="menu-text"> <?php echo $m['title']; ?> </span>
							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						<?php if(isset($m['children'])): ?>
						<ul class="submenu">
							
							<?php if(is_array($m['children']) || $m['children'] instanceof \think\Collection || $m['children'] instanceof \think\Paginator): if( count($m['children'])==0 ) : echo "" ;else: foreach($m['children'] as $key=>$vo): ?>   
							<li class="hover">
								<a href="<?php echo \think\Request::instance()->root(true); ?>/<?php echo $vo['url']; ?>">
									<i class="menu-icon fa fa-caret-right"></i>
									<?php echo $vo['title']; if(isset($vo['children'])): ?>
										<b class="arrow fa fa-angle-down"></b>
									<?php endif; ?>
								</a>

								<b class="arrow"></b>
								
									<?php if(isset($vo['children'])): ?>
										<ul class="submenu">
											<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): if( count($vo['children'])==0 ) : echo "" ;else: foreach($vo['children'] as $key=>$v2): ?> 
												<li class="hover">
													<a href="<?php echo \think\Request::instance()->root(true); ?>/<?php echo $v2['url']; ?>">
														<i class="menu-icon fa fa-caret-right"></i>
														<?php echo $v2['title']; ?>
													</a>
			
													<b class="arrow"></b>
												</li> 
											<?php endforeach; endif; else: echo "" ;endif; ?> 
										</ul>	
									<?php endif; ?>
								
							</li>
							<?php endforeach; endif; else: echo "" ;endif; ?>
							
						</ul>
						<?php endif; ?>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					
				</ul><!-- /.nav-list -->

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
							

<div class="page-header">
	<h1>	
		<?php echo $breadcrumb1; ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			<?php echo $breadcrumb2; ?>
		</small>
	</h1>
</div>

<div class="page-header">	
	<a href="<?php echo url('Goods/add'); ?>" class="btn btn-primary">新增</a>
	<span href="<?php echo url('Goods/copy_goods'); ?>" class="btn btn-primary copy">复制</span>
</div>	
	
<table class="table table-striped table-bordered table-hover search-form">
	<thead>
		<input name="type" type="hidden"  value="search" />
		<th><input name="name" type="text" placeholder="输入商品名称" value="<?php echo input('name'); ?>" /></th>
		<th>    				
			<select name="category">
				<option value="">全部分类</option>			
				<?php $input_cid=input('category'); if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat): $mod = ($i % 2 );++$i;?>
					<option <?php if($input_cid == $cat['id']): ?> selected="selected"<?php endif; ?> value="<?php echo $cat['id']; ?>"><?php echo $cat['title_show']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
				
			</select>
		</th>	
		<th>    				
			<select name="status">
				<option value="">全部状态</option>							
				<option <?php if(input('status') == 1): ?> selected="selected"<?php endif; ?>value="1">启用</option>
				<option <?php if(input('status') == 2): ?> selected="selected"<?php endif; ?> value="2">停用</option>
			</select>
		</th>
		<th>
			<a class="btn btn-primary" href="javascript:;" id="search" url="<?php echo url('Goods/index'); ?>">查询</a>
		</th>
	</thead>
</table>	
	

	
<div class="row">
	<div class="col-xs-12">	
		<div>
			<table id="table" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center">
							<label>
								<input type="checkbox" class="ace check-all" />
								<span class="lbl"></span>
							</label>
						</th>											
						<th>ID</th> 
						<th>图片</th> 
						<th>商品名称</th> 			
						<th>价格</th>
						<th>数量</th>
						<th>排序</th>
						<th>状态</th>						
						<th>操作</th>				
					</tr>
				</thead>
				<tbody>
						<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<tr>		
							<td class="center">
							<label>
								<input class="ace ids" type="checkbox" name="id[]" value="<?php echo $v['goods_id']; ?>"/>
								<span class="lbl"></span>
							</label>
							</td>				
							<td><?php echo $v['goods_id']; ?></td>
							<td>
								<?php if($v['image']): ?>
		                  			<img src="/<?php echo resize($v['image'],50,50); ?>" />
			                  	<?php else: ?>
			                  		<img src="/public/static/image/no_image_50x50.jpg" />
			                  	<?php endif; ?>
							</td>
							<td><?php echo $v['name']; if($v['shipping'] == 0): ?>
								<span class="warning">免配送</span> 								
								<?php endif; if($v['subtract'] == 0): ?>
								<span class="warning">不减库存</span> 								
								<?php endif; ?>
							</td>
							
							<td>
								<input name="price" type="text" size="2" class="price" goods_id='<?php echo $v['goods_id']; ?>' value="<?php echo $v['price']; ?>" />
							</td>
							<td>							
								<input name="quantity" type="text" size="2" class="quantity" goods_id='<?php echo $v['goods_id']; ?>' value="<?php echo $v['quantity']; ?>" />
							</td>
							<td>
								<input name="sort" type="text" size="1" class="sort" goods_id='<?php echo $v['goods_id']; ?>' value="<?php echo $v['sort_order']; ?>" />								
							</td>
							<td>
								<?php switch($v['status']): case "1": ?><a href='<?php echo url("Goods/set_status",array("id"=>$v["goods_id"],"status"=>2)); ?>'><span class="btn btn-xs btn-info"><i class="fa fa-unlock bigger-120"></i></span></a><?php break; case "2": ?><a href='<?php echo url("Goods/set_status",array("id"=>$v["goods_id"],"status"=>1)); ?>'><span class="btn btn-xs btn-danger"><i class="fa fa-lock bigger-120"></i></span></a><?php break; endswitch; ?>
							</td>
							<td>
								
								
								<div class="btn-group">
									<button class="btn btn-xs btn-info">
										<i class="fa fa-edit bigger-120"></i>
									</button>
									<button class="btn btn-info btn-xs dropdown-toggle no-radius" data-toggle="dropdown" aria-expanded="false">
										<span class="bigger-110 ace-icon fa fa-caret-down icon-only"></span>
									</button>
									<ul class="dropdown-menu dropdown-info dropdown-menu-right">																
										<li>
											<a href='<?php echo url("Goods/edit_general",array("id"=>$v["goods_id"])); ?>'>基本信息</a>
										</li>									
																		
										<li>
											<a href='<?php echo url("Goods/edit_links",array("id"=>$v["goods_id"])); ?>'>关联</a>
										</li>
										<li>
											<a href='<?php echo url("Goods/edit_option",array("id"=>$v["goods_id"])); ?>'>选项</a>
										</li>
										<li>
											<a href='<?php echo url("Goods/edit_discount",array("id"=>$v["goods_id"])); ?>'>折扣</a>
										</li>
										<li>
											<a href='<?php echo url("Goods/edit_image",array("id"=>$v["goods_id"])); ?>'>商品相册</a>
										</li>
										<li>
											<a href='<?php echo url("Goods/edit_mobile",array("id"=>$v["goods_id"])); ?>'>手机端描述</a>
										</li>									
									</ul>
								</div>
								
								<a class="delete btn btn-xs btn-danger" href='<?php echo url("Goods/del",array("id"=>$v["goods_id"])); ?>' >
									<i class="fa fa-trash bigger-120"></i>
								</a>
								
							</td>
						</tr>
						<?php endforeach; endif; else: echo "$empty" ;endif; ?>
						
						<tr>
							<td colspan="20" class="page"><?php echo $list->render(); ?></td>
						</tr>
						<tr>
							<td colspan="20">总计 <?php echo ($list->total() ?: "0"); ?> 个商品</td>
						</tr>
				</tbody>
				
			</table>
		</div>
	</div>
</div>

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->



			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='/public/static/view_res/admin/ace/js/jquery1x.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/public/static/view_res/admin/ace/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='/public/static/view_res/admin/ace/js/jquery1x.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/public/static/view_res/admin/ace/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="/public/static/js/bt/bootstrap.min.js"></script>

		<!-- ace scripts -->
		<script src="/public/static/view_res/admin/ace/js/ace-elements.min.js"></script>
		<script src="/public/static/view_res/admin/ace/js/ace.min.js"></script>

		
<style>
.warning{
	border:1px solid red;
	color:#f60;
}	
</style>
<script>
$(function(){

	$('.price').blur(function(){
		
		$.post(
			"<?php echo url('Goods/update_price'); ?>",
			{price:$(this).val(),goods_id:$(this).attr('goods_id')},
			function(data){
				if(data){
					window.location.reload();
				}
			}
		);
	});
	
	$('.quantity').blur(function(){
		
		$.post(
			"<?php echo url('Goods/update_quantity'); ?>",
			{quantity:$(this).val(),goods_id:$(this).attr('goods_id')},
			function(data){
				if(data){
					window.location.reload();
				}
			}
		);
	});
	
	$('.sort').blur(function(){
		
		$.post(
			"<?php echo url('Goods/update_sort'); ?>",
			{sort:$(this).val(),goods_id:$(this).attr('goods_id')},
			function(data){
				if(data){
					window.location.reload();
				}
			}
		);
	});
	
	
	$("#search").click(function () {
        var url = $(this).attr('url');
        var query = $('.search-form').find('input,select').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
        query = query.replace(/^&/g, '');
        if (url.indexOf('?') > 0) {
            url += '&' + query;
        } else {
            url += '?' + query;
        }
        window.location.href = url;
    });
	
	$('.copy').click(function(){
		
		if($('input:checked').size()==0){
			alert('请选择一个商品');
			return ;
		}
		
		$.ajax({
		url: "<?php echo url('Goods/copy_goods'); ?>",
		type: 'post',
		data: $('input:checked'),
		dataType: 'json',
		beforeSend: function() {
			$('.copy').attr('disabled', true);
			$('.copy').after('<span class="wait">&nbsp;<img src="/public/static/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('.copy').attr('disabled', false); 
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
					
			if (json['redirect']) {				
				
				location = json['redirect']
								
			} 
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert('修改失败');
		}
	});	
	});
});		
</script>

	</body>
</html>

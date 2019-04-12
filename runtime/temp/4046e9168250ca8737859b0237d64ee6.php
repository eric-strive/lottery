<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./themes/default/mobile/custom_menu/index.html";i:1555075681;s:37:"./oscshop/admin/view/public/base.html";i:1555075680;}*/ ?>
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
	移动端
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		<?php echo $breadcrumb1; ?>
	</small>
	
</h1>
</div>

<div class="page-header">
<a href="<?php echo url('CustomMenu/delete_menu'); ?>" class="delete btn btn-primary">清空菜单</a>
<button id="send" form="menu" type="submit" style="float:right;"  class="btn btn-sm btn-primary">提交生成</button>	
</div>	

<div class="row">
	<form id="menu" action="<?php echo url('CustomMenu/create_menu'); ?>" method="post">
	<?php if(empty($menu)): $__FOR_START_792442510__=0;$__FOR_END_792442510__=3;for($i=$__FOR_START_792442510__;$i < $__FOR_END_792442510__;$i+=1){ ?>
	<div class="col-xs-12">	
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">  	
					<tr>
						<th class="col-xs-2">第<?php echo $i+1; ?>列菜单类型</th>
						<th class="col-xs-5">第<?php echo $i+1; ?>列菜单名称</th>
						<th class="col-xs-5">第<?php echo $i+1; ?>列菜单内容</th>
						<th></th>
					</tr>
					<tbody id="tbody<?php echo $i; ?>">
					<tr>
						<td>							
							<select class="form-control" name="type[<?php echo $i; ?>][0]" id="select<?php echo $i; ?>">								
								<?php if(is_array($menu_type) || $menu_type instanceof \think\Collection || $menu_type instanceof \think\Paginator): if( count($menu_type)==0 ) : echo "" ;else: foreach($menu_type as $key=>$m): ?>
								<option value="<?php echo $key; ?>"><?php echo $m['name']; ?></option>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
						<td>
							<input type="text" name="name[<?php echo $i; ?>][0]" value="" class="form-control">
						</td>
						<td>
							<input type="text" name="content[<?php echo $i; ?>][0]" value="" class="form-control">
						</td>
					</tr>
					</tbody>
					<tr>
						<td colspan="3">
							<div class="btn btn-primary " bid='0' id="b<?php echo $i; ?>" onclick="add_child_menu('<?php echo $i; ?>');">添加子菜单</div>
						</td>
					</tr>
				</table>
			
		</div>
	</div>
	<?php } else: if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $k = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?>
		<div class="col-xs-12">	
			<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">  	
						<tr>
							<th class="col-xs-2">第<?php echo $k; ?>列菜单类型</th>
							<th class="col-xs-5">第<?php echo $k; ?>列菜单名称</th>
							<th class="col-xs-5">第<?php echo $k; ?>列菜单内容</th>
							<th></th>
						</tr>
						<tbody id="tbody<?php echo $k-1; ?>">
						<?php if(empty($menu['sub_button'])): ?>	
						<tr>
							<td>							
								<select class="form-control" name="type[<?php echo $k-1; ?>][0]" id="select<?php echo $k-1; ?>">								
									<?php if(is_array($menu_type) || $menu_type instanceof \think\Collection || $menu_type instanceof \think\Paginator): if( count($menu_type)==0 ) : echo "" ;else: foreach($menu_type as $key=>$m): ?>
									<option <?php if($m['type'] == $menu['type']): ?> selected="selected"<?php endif; ?>  value="<?php echo $key; ?>"><?php echo $m['name']; ?></option>
									<?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</td>
							<?php if($menu['type'] == 'view'): ?>
								<td>
									<input type="text" name="name[<?php echo $k-1; ?>][0]" value="<?php echo $menu['name']; ?>" class="form-control">
								</td>
								<td>
									<input type="text" name="content[<?php echo $k-1; ?>][0]" value="<?php echo $menu['url']; ?>" class="form-control">
								</td>
							<?php endif; ?>
						</tr>
						
						<?php else: ?>
							<tr>
								<td>							
									<select class="form-control" name="type[<?php echo $k-1; ?>][0]" id="select<?php echo $k-1; ?>">								
											<?php if(is_array($menu_type) || $menu_type instanceof \think\Collection || $menu_type instanceof \think\Paginator): if( count($menu_type)==0 ) : echo "" ;else: foreach($menu_type as $key=>$m): ?>
											<option  value="<?php echo $key; ?>"><?php echo $m['name']; ?></option>
											<?php endforeach; endif; else: echo "" ;endif; ?>
										</select>
								</td>
								
								<td>
									<input type="text" name="name[<?php echo $k-1; ?>][0]" value="<?php echo $menu['name']; ?>" class="form-control">
								</td>
								<td>
									<input type="text" name="content[<?php echo $k-1; ?>][0]" value="" class="form-control">
								</td>
								
							</tr>
							<?php $sb_key=0; if(is_array($menu['sub_button']) || $menu['sub_button'] instanceof \think\Collection || $menu['sub_button'] instanceof \think\Paginator): $i = 0; $__LIST__ = $menu['sub_button'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m1): $mod = ($i % 2 );++$i;$sb_key+=1; ?>
								<tr id="<?php echo $k-1; ?>input<?php echo $sb_key; ?>">
									<td>							
										<select class="form-control" name="type[<?php echo $k-1; ?>][<?php echo $sb_key; ?>]" id="select<?php echo $sb_key; ?>">								
											<?php if(is_array($menu_type) || $menu_type instanceof \think\Collection || $menu_type instanceof \think\Paginator): if( count($menu_type)==0 ) : echo "" ;else: foreach($menu_type as $key=>$m): ?>
											<option <?php if($m1['type'] == $m['type']): ?> selected="selected"<?php endif; ?>  value="<?php echo $key; ?>"><?php echo $m['name']; ?></option>
											<?php endforeach; endif; else: echo "" ;endif; ?>
										</select>
									</td>
									<?php if($m1['type'] == 'view'): ?>
										<td>
											<input type="text" name="name[<?php echo $k-1; ?>][<?php echo $sb_key; ?>]" value="<?php echo $m1['name']; ?>" class="form-control">
										</td>
										<td>
											<input type="text" name="content[<?php echo $k-1; ?>][<?php echo $sb_key; ?>]" value="<?php echo $m1['url']; ?>" class="form-control">
										</td>
									<?php else: ?>
										<td>
											<input type="text" name="name[<?php echo $k-1; ?>][<?php echo $sb_key; ?>]" value="<?php echo $m1['name']; ?>" class="form-control">
										</td>
										<td>
											<input type="text" name="content[<?php echo $k-1; ?>][<?php echo $sb_key; ?>]" value="<?php echo (isset($m1['url']) && ($m1['url'] !== '')?$m1['url']:''); ?>" class="form-control">
										</td>
									<?php endif; ?>
									<td><button type="button" onclick="$('#'+'<?php echo $k-1; ?>'+'input'+'<?php echo $sb_key; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
								</tr>
							<?php endforeach; endif; else: echo "" ;endif; endif; ?>
						
						
						
						</tbody>
						<tr>
							<td colspan="3">
								<div class="btn btn-primary " bid='0' id="b<?php echo $k-1; ?>" onclick="add_child_menu('<?php echo $k-1; ?>');">添加子菜单</div>
							</td>
						</tr>
					</table>
				
			</div>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; endif; ?>
	
	</form>
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

		
<script>
function add_child_menu(id){
	
	var num=$('#b'+id).attr('bid');

	num=parseInt(num)+1;
	
	$('#b'+id).attr('bid',num);
	
	var select=$('#select'+id).html();
	var html='';
	html+='<tr id="'+id+'input'+num+'">';
	html+='<td><select class="form-control" name="type['+id+']['+num+']">';
	html+=select;	
	html+='</select></td>';
	html+='<td><input type="text" name="name['+id+']['+num+']" value="" class="form-control"></td>';	
	html+='<td><input type="text" name="content['+id+']['+num+']" value="" class="form-control"></td>';
	html+='<td><button type="button" onclick="$(\'#'+id+'input'+num+'\').remove();" class="btn btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
//	alert(html);
	$('#tbody'+id).append(html);
}
</script>							

	</body>
</html>

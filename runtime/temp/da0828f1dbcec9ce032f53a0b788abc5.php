<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./oscshop/admin/view/settings/other.html";i:1555075680;s:53:"/var/www/oscshop2/oscshop/admin/view/public/base.html";i:1555075680;}*/ ?>
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
<div class="row">
	<div class="col-xs-12">	
		<div class="form-horizontal">	
			<legend>系统参数 </legend>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">page_num </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<input class="col-xs-10 col-sm-10 form-control" name="page_num" type="text" value="<?php echo \think\Config::get('page_num'); ?>" >
				</div>
			</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">是否保存用户行为 </label>
			<div class="col-sm-10">
				<div class="clearfix">					
						<label class="radio-inline"><input type="radio" checked="checked" value="true" name="storage_user_action">是</label>	
						<label class="radio-inline"><input type="radio" value="false" name="storage_user_action">否</label>				
				</div>
			</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">会员登录状态持久化 </label>
			<div class="col-sm-10">
				<div class="clearfix">					
						<label class="radio-inline"><input type="radio" checked="checked" value="cookie" name="member_login_type">是</label>	
						<label class="radio-inline"><input type="radio" value="session" name="member_login_type">否</label>				
				</div>
			</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">注册需要审核 </label>
			<div class="col-sm-10">
				<div class="clearfix">					
						<label class="radio-inline"><input type="radio" checked="checked" value="1" name="reg_check">是</label>	
						<label class="radio-inline"><input type="radio" value="0" name="reg_check">否</label>				
				</div>
			</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">默认会员组 </label>
			<div class="col-sm-10">
				<div class="clearfix">					
						<select class="form-control" name="default_group_id">							
						<?php if(is_array($member_auth_group) || $member_auth_group instanceof \think\Collection || $member_auth_group instanceof \think\Paginator): $i = 0; $__LIST__ = $member_auth_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?>
							<option <?php if($group['id'] == config('default_group_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $group['id']; ?>"><?php echo $group['title']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>				
						</select>		
				</div>
			</div>
			</div>
			
		
			<legend>单位参数 </legend>
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">  长度单位 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="length_id">							
						<?php if(is_array($length) || $length instanceof \think\Collection || $length instanceof \think\Paginator): $i = 0; $__LIST__ = $length;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$length): $mod = ($i % 2 );++$i;?>
							<option <?php if($length['length_class_id'] == config('length_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $length['length_class_id']; ?>"><?php echo $length['title']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>				
					</select>
				</div>
			</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label no-padding-left">  重量单位 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="weight_id">							
						<?php if(is_array($weight) || $weight instanceof \think\Collection || $weight instanceof \think\Paginator): $i = 0; $__LIST__ = $weight;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$weight): $mod = ($i % 2 );++$i;?>
							<option <?php if($weight['weight_class_id'] == config('weight_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $weight['weight_class_id']; ?>"><?php echo $weight['title']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>				
					</select>
				</div>
			</div>
			</div>
			<legend>订单状态 </legend>
			<div class="form-group">			
				<label class="col-sm-2 control-label no-padding-left"> 默认订单状态 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="default_order_status_id">						
						<?php if(is_array($order_status) || $order_status instanceof \think\Collection || $order_status instanceof \think\Paginator): $i = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$status): $mod = ($i % 2 );++$i;?>
							<option <?php if($status['order_status_id'] == config('default_order_status_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>					
					</select>
				</div>
			</div>
			</div>
			
			<div class="form-group">			
				<label class="col-sm-2 control-label no-padding-left"> 订单付款状态 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="paid_order_status_id">						
						<?php if(is_array($order_status) || $order_status instanceof \think\Collection || $order_status instanceof \think\Paginator): $i = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$status): $mod = ($i % 2 );++$i;?>
							<option <?php if($status['order_status_id'] == config('paid_order_status_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>					
					</select>
				</div>
			</div>
			</div>
			
			<div class="form-group">			
				<label class="col-sm-2 control-label no-padding-left"> 订单完成状态 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="complete_order_status_id">						
						<?php if(is_array($order_status) || $order_status instanceof \think\Collection || $order_status instanceof \think\Paginator): $i = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$status): $mod = ($i % 2 );++$i;?>
							<option <?php if($status['order_status_id'] == config('complete_order_status_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>					
					</select>
				</div>
			</div>
			</div>
			
			<div class="form-group">			
				<label class="col-sm-2 control-label no-padding-left"> 订单取消状态 </label>
			<div class="col-sm-10">
				<div class="clearfix">
					<select class="form-control" name="cancel_order_status_id">						
						<?php if(is_array($order_status) || $order_status instanceof \think\Collection || $order_status instanceof \think\Paginator): $i = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$status): $mod = ($i % 2 );++$i;?>
							<option <?php if($status['order_status_id'] == config('cancel_order_status_id')): ?> selected="selected"<?php endif; ?> value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>					
					</select>
				</div>
			</div>
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-left"> </label>	
			<div class="col-sm-11">
				<button form="form" type="submit" id="send"  class="btn btn-sm btn-primary">提交</button>		
			</div>
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

		
<script>
$(function(){
	Oscshop.setValue("storage_user_action", '<?php echo \think\Config::get('storage_user_action'); ?>');		
	Oscshop.setValue("member_login_type",'<?php echo \think\Config::get('member_login_type'); ?>');		
	Oscshop.setValue("reg_check",'<?php echo \think\Config::get('reg_check'); ?>');
});	
$('#send').click(function(){
	$.post(
		"<?php echo url('Settings/save'); ?>",
		$('input[type=\'text\'],input[type=\'hidden\'],input[type=\'radio\']:checked,textarea,select'),
		function(d){
			art.dialog({
					content:d.success,
					lock: true,
					ok: function () {	 		
				 	  reloadPage(window);    	
				      return false;
				    }
				});	
		}
	);
});
</script>	

	</body>
</html>

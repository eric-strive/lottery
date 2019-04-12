<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:36:"./oscshop/admin/view/menu/index.html";i:1555075680;s:53:"/var/www/oscshop2/oscshop/admin/view/public/base.html";i:1555075680;}*/ ?>
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
	<a id="addParent" class="btn btn-primary">新增</a>
	<a id="edit" class="btn btn-primary">编辑</a>
	<a id="remove" class="btn btn-primary">删除</a>
</div>

<div class="row">
	<div id="category_tree" class="ztree"></div>
</div>

	
	<div id="dialog" class="dialog" style="display:none">
    <div class="dialog_content">
    
        <dl>
        	<dt>菜单名称</dt>
        	<dd><input type="text" name="title" class="text" /></dd>
        	<dt>URL</dt>
    		<dd><input name="url" type="text" value="" size="50"  /></dd>    	
    		
    		<dt>模块</dt>
    		<dd><input name="module" type="text" value=""  /></dd>  
    		
    		<dt>图标</dt>
    		<dd><input name="icon" type="text" value=""  /></dd> 
    		
    		<dt>类型</dt>
    		<dd>
    			<label>
    				<input id="nav" name="type" type="radio" value="nav" checked="checked" /> 显示 
    			</label>
    			<label>
    				<input id="auth" name="type" type="radio" value="auth"  /> 不显示 
    			</label>
    		</dd>   
    			
    		<dt>排序</dt>
    		<dd>
    			<input name="sort_order" type="text" value="" />
    		</dd>
    		
    		
        </dl>       
   
      
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

		
<script src="/public/static/ztree/jquery.ztree.all-3.5.min.js"></script>
<link  rel="stylesheet" type="text/css" href="/public/static/ztree/zTreeStyle.css" />
<link  rel="stylesheet" type="text/css" href="/public/static/ztree/tree.css" />
<script>
		var setting = {
			view: {
				addHoverDom: false,
				removeHoverDom: false,
				selectedMulti: false
			},
			edit: {
				enable: true,
				editNameSelectAll: true,
				showRemoveBtn: false,
				showRenameBtn: false
			},
			data: {
				simpleData: {
					enable: true
				}
			}
		};

	
	
	var zNodes =<?php echo $list; ?>;


function save(type){
		var zTree = $.fn.zTree.getZTreeObj("category_tree"),

		nodes = zTree.getSelectedNodes(),
		treeNode = nodes[0];

		if(treeNode!=undefined){
			var isp= nodes[0].isParent;
		}else{
			var isp= true;
		}			
		var id=(treeNode==undefined?0:treeNode.id);

		if(type=='add'){
			url='<?php echo url("Menu/add"); ?>';
		}else if(type=='edit'){
			url='<?php echo url("Menu/edit"); ?>';
		}

		$.post(
			url,
			{					
				'id':id,
				'title':$("input[name='title']").val(),
				'url':$("input[name='url']").val(),
				'sort_order':$("input[name='sort_order']").val(),		
				'module':$("input[name='module']").val(),	
				'icon':$("input[name='icon']").val(),	
				'type':$("input[name='type']:checked").val(),			
				
				
			},
			function(d){	
				
			    if(type=='add'){						
						if(d.error){
							alert(d.error);
						
						}else if(d.status=='success'){											
							//有父节点
					    	if(treeNode){
					    		treeNode = zTree.addNodes(treeNode, {id:d.id, pId:id, isParent:isp, name:d.name});
					    	}else{
					    		treeNode = zTree.addNodes(treeNode, {id:d.id, pId:0, isParent:isp, name:d.name});
					    	}
					    	
					    	close_artDialog();
					    }	
					}else if(type=='edit'){
						
						if(d.success){
							nodes[0].name = d.name;
							zTree.updateNode(nodes[0]);
							
							close_artDialog();
						}
						if(d.error){
							alert(d.error);
						}
						
						
					}
			}
		);	
}

function add(e) {
	
	var dialog=$('#dialog').html();
	
	var title='新增菜单';
	
	art.dialog({
		title: title,
		content:dialog,
		lock: true,
		ok: function () {	 		
	 	  save('add');	    	
	      return false;
	    },
	    cancelVal: '关闭',
		cancel: true 
	});	
}
function edit() {
	
	var zTree = $.fn.zTree.getZTreeObj("category_tree"),
		nodes = zTree.getSelectedNodes(),
		treeNode = nodes[0];
		if (nodes.length == 0) {
			alert("请先选择一个节点");
			return;
		}else{
			var id=treeNode.id;
			$.post(
				'<?php echo url("Menu/get_info"); ?>',
				{					
					'id':id,
					
				},
				function(d){			
					$("input[name='title']").val(d.title);
					$("input[name='url']").val(d.url);
					$("input[name='sort_order']").val(d.sort_order);
					$("input[name='module']").val(d.module);
					$("input[name='icon']").val(d.icon);
					
					
					if(d.type=='nav'){
						$("#nav").attr("checked","checked");
					}else if(d.type=='auth'){
						$("#auth").attr("checked","checked");
					}
				}
			);				
		}
			
	var dialog=$('#dialog').html();

	var title='修改菜单';
	
	art.dialog({
		title: title,
			content:dialog,
			lock: true,
			ok: function () {
				save('edit');
				return false;
			},
		    cancelVal: '关闭',
			cancel: true 
	});	
				
	
}
function remove(e) {
	
			if(!confirm('确认要删除吗！！')){
				return false;
			}
			
			var zTree = $.fn.zTree.getZTreeObj("category_tree"),
			nodes = zTree.getSelectedNodes(),
			treeNode = nodes[0];
			if (nodes.length == 0) {
				alert("请先选择一个节点");
				return;
			}
			$.post(
				'<?php echo url("Menu/del"); ?>',
				{					
					'id':treeNode.id,					
				},
				function(d){			
					if(d.error){
						alert(d.error);
					}else{						
						zTree.removeNode(treeNode);
					}
					
					
				}
			);
}


$(document).ready(function(){
	$.fn.zTree.init($("#category_tree"), setting, zNodes);
	$("#addParent").bind("click", {isParent:true}, add);
	$("#edit").bind("click", edit);
	$("#remove").bind("click", remove);
});
	
</script>

	</body>
</html>

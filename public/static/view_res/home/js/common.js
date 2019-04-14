function get_url_attr(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
} 
$(document).ready(function() {
    $("ul.nav li").hover(function(){
        $(this).addClass("on");
    },
    function(){
        $(this).removeClass("on");
    });    
});	

$('#reg').click(function(){
	art.dialog.open("/reg", 
		{	id:'reg',
			title: '注册',
			lock: true
		});
});
$('#login').click(function(){
	art.dialog.open("/login", 
		{	id:'login',
			title: '登录',
			lock: true
		});
});
var root_url='/';
template.config('openTag', '<@');
template.config('closeTag', '@>')

function get_url_val(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}




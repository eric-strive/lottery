function bounceInDown(ele){
	$(ele).siblings('div').removeClass('none');
	$(ele).siblings('div').children('div').first().addClass('bounceInDown');
}

function show(ele){
	$(ele).siblings('div').removeClass('none');
}

function bounceInUp(ele){
	$(ele).parent('div').parent('div').addClass('none');
	$(ele).parent('div').removeClass('bounceInDown');
}

function bounceInUptwo(ele){
	$(ele).parent('div').addClass('none');
	$(ele).parent('div').removeClass('bounceInDown');
	$(ele).parent('div').siblings('div').show();
	$(ele).parent('div').siblings('div').children('div').addClass('bounceInDown');
}

function hide(ele){
	$(ele).parent('div').parent('div').addClass('none');
	$(ele).parent('div').removeClass('bounceInDown');
}



$(function(){
	var goods_id = get_url_val("id");

    //图片轮播
    function picSwipe() {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            paginationClickable: true,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: 2500,
            autoplayDisableOnInteraction: false
        });
    }
	 $.ajax({
	 	url:root_url+"mobile/goods/get_goods_detail",
       type:"get",
       data:{goods_id:goods_id},
       dataType:"json",
       success:function(result){
       	
       		var data = result;       		
       		            
            var html = template('detail', data);
                                      
            $("#content").html(html);
           picSwipe()
       }	 	
	 });
	 
	 

	 
});



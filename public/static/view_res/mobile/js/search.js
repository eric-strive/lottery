
require(['config'], function(config) {

    require(['util', 'jquery'], function(util, $) {

        var suload = false;
        var plistShowType = 'hoz';
        var dontload = true;
        var loadingLock = false;

        // 初始化加载列表
        if ($('#product_list').length > 0 && $('#orderDetailsWrapper').length !== 1) {
            window.pdPageNo = 0;
            window.listLoading = false;
            // init list
            loadProductList(pdPageNo);
            // onscroll bottom
           $(window).scroll(function() {
               totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 150;
               if ($(document).height() <= totalheight && !listLoading) {
                   //加载数据
                   loadProductList(++pdPageNo);
               }
           });
        }

        // subnav
        util.fnTouchEnd('.subnav', function(node) {
            loadingLock = false;
            var orderby = node.attr('orderby');
            $('.active').removeClass('active');
            window.pdPageNo = 0;
            var priceB = node.find('b._priceB');
            node.addClass('active');
            node.find('b._priceB').toggleClass('up');
            if (priceB.length !== 0) {
                orderby += priceB.hasClass('up') ? " DESC" : " ASC";
            } else {
                orderby += " DESC";
            }
            loadProductList(0);
            $('#orderby').val(orderby);
            $('#product_list').html("");
        });

        if ($('#orderby').val() === '`sale_count`') {
        } else {
            $('.subnav').eq(0).addClass('active');
        }

        function loadProductList(page) {
            if (!loadingLock) {
                // params
                var searchKey = $('#searchBox').val();
                
                var orderby=$('.active').attr('orderby');
				
				if(!orderby){
					orderby='goods_id';
				}
                
                // request uri
                var _url = '/mobile/search/ajax_goods_list/page/' + parseInt(page)+ '/searchKey/' +encodeURIComponent(searchKey)+ '/orderby/' + orderby;  
                
                listLoading = true;
              //  $('.emptyTip').html('');
                $('#list-loading').show();
                $.get(_url, function(HTML) {
                    $('#list-loading').hide();
                    if (HTML === '') {
                       listLoading=true;
                       $("#product_list").append('<div class="emptyTip">暂无数据</div>');
                    } else if (HTML !== '') {
                        suload = true;
                        listLoading = false;
                        HTML = $(HTML);
                        var patch = $('.patch', HTML);
                        patch.parent().addClass('rm');
                        $('#product_list .pdBlock').last().append(patch);
                        $("#product_list").append(HTML);
                        $('.rm').remove();
                        $('.productIW').height($('.productIW').width());
                        $('.productList img').each(function() {
                            $(this).height($(this).width());
                        });
                        $('.subcat_item').each(function(i, node) {
                            $(node).find('img').each(function() {
                                $(this).height($(this).width());
                            });
                        });
                    }
                    
                    searchKey = null;
                    _url = null;
                });
            }
        }

    });
});
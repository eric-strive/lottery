
require(['config'], function (config) {

    require(['util', 'jquery', 'Spinner'], function (util, $, Spinner) {

        var qrcodeLoaded = false;

        $('#companyQrcode').click(function (node) {
            $('#wrapper').show();
            if (!qrcodeLoaded) {
                Spinner.spin($('#wrapper').get(0));
                // [HttpPost]
                $.post('/mobile/user/get_qr_code', {}, function (url) {
                    Spinner.stop();
                 
                    $('#wrapper').append("<img src='" + url + "' /><p>长按二维码，点击发送给朋友</p>");
                    $('#wrapper img').on('load', function () {
                        $(this).animate({
                            marginTop: ($(window).height() - 250) / 2 - 35
                        }, 500);
                    });
                });
                qrcodeLoaded = true;
            }
            $('#wrapper .close').click(function () {
                $('#wrapper').hide();
            });
        });

    });
});
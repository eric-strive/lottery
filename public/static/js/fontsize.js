function page() {

	//通过navigator判断是否是移动设备
	if ((navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i))) {
	//在移动端
		(function (doc, win) {
		// html
			var docEl = doc.documentElement,
			resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
			recalc = function () {
				var clientWidth = docEl.clientWidth;
				if (!clientWidth) return;
				clientWidth = (clientWidth > 768 ) ? 768 : clientWidth ; docEl.style.fontSize = 20 * (clientWidth / 375 ) + 'px';      //这个10可以根据自己使用的数据来调整
			};

			if (!doc.addEventListener) return;
			win.addEventListener(resizeEvt, recalc, false);
			recalc();

			})(document, window);
		//移动端 文字适配
		}
	else{       //如果是pc端我们可以// 绑定到窗口的这个事件中
		window.onresize=function() {
			  var whdef = 100/1920;// 表示1920的设计图,使用100PX的默认值
			  var wH = window.innerHeight;// 当前窗口的高度
			  var wW = window.innerWidth;// 当前窗口的宽度
			  var rem = wW * whdef;// 以默认比例值乘以当前窗口宽度,得到该宽度下的相应FONT-SIZE值
			  $("html").css("font-size", rem + "px");
			}
	}
}
page();
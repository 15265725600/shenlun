var para = window.location.search;
//页面地址前缀
function preUrl(path) {
//		var fUrl = 'http://127.0.0.1:8020/hm_slpg/';
	var fUrl = 'http://192.168.2.74:8020/hm_slpg/';
	//		var fUrl = 'http://slpg.lgwy.net/hm_shenlun/web/Webservice/hm_slpg/';
	return fUrl + path;
}
//重新登录后点击返回键
function backUrl() {
	var prevUrl = document.referrer.split('?')[0];
	var Url = "http://slpg.lgwy.net/web/Webservice/hm_slpg/log/login.html";
	if(prevUrl == Url) {
		$('.backUrl').attr('href', 'javascript:history.go(-2);');
	}
}
/*
 *  获取地址栏中的参数
 *   GetQueryString('参数名1')
 *   GetQueryString('参数名2')
 */
function GetQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r != null) return unescape(r[2]);
	return null;
}

//ajax 传参url
function reqUrl(path) {
	//	var frontUrl = 'http://192.168.2.146:8008/group19/hm_shenlun/index.php/Webservice/V100/';
	var frontUrl = 'http://slpg.lgwy.net/index.php/Webservice/V100/';
//	var frontUrl = 'http://47.92.136.34/hm_shenlun/index.php/Webservice/V100/';
	return frontUrl + path;
}

var token;

//设置cookie
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires=" + d.toGMTString();
	document.cookie = cname + "=" + encodeURIComponent(cvalue) + "; " + expires + ";path=/";
	console.log(document.cookie);
}

//获取cookie
function getCookie(cname) {
	var name = cname + "=";
	//  console.log(document.cookie);
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i].trim();

		if(c.indexOf(name) == 0)
			return decodeURIComponent(c.substring(name.length, c.length));
	}
	return "";
}

function delCookie(name) {
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval = getCookie(name);
	if(cval != null)
		document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}
//localstorage
//保存永久数据
function setInfo(k, v) {
	window.localStorage.setItem(k, JSON.stringify(v));
}
//读取永久数据
function getInfo(k) {
	var data = window.localStorage.getItem(k);
	return JSON.parse(data);
}
//删除永久数据
function removeInfo(k) {
	window.localStorage.removeItem(k);
}
//页面链接跳转历史URL不记录的兼容处理
var fnUrlReplace = function(eleLink) {
	if(!eleLink) {
		return;
	}
	var href = eleLink.href;
	if(href && /^#|javasc/.test(href) === false) {
		if(history.replaceState) {
			history.replaceState(null, document.title, href.split('#')[0] + '#');
			location.replace('');
		} else {
			location.replace(href);
		}
	}
};

function returnFloat(value) {
	var value = Math.round(parseFloat(value) * 100) / 100;
	var xsd = value.toString().split(".");
	if(xsd.length == 1) {
		value = value.toString() + ".00";
		return value;
	}
	if(xsd.length > 1) {
		if(xsd[1].length < 2) {
			value = value.toString() + "0";
		}
		return value;
	}
}
/** picker底部滑动选择数据（一列，两列）
 *   arr: 数据
 *   nameEl: 点击对象
 *   valEl: 保存所选值
 *   oper: 区分 value 和 innerHTMl
 *   title: 顶部标题
 */

function pickerShow(arr, nameEl, valEl, oper, title) {
	var picker = new Picker({
		data: arr,
		selectedIndex: [0, 1],
		title: title
	});

	picker.on('picker.select', function(selectedVal, selectedIndex) {
		valEl.innerHTML = '';

		for(var i = 0; i < arr.length; i++) {
			if(oper === 0) {
				valEl.value += arr[i][selectedIndex[i]].text;
			} else {
				valEl.innerHTML += arr[i][selectedIndex[i]].text;
			}
		}
	});

	nameEl.addEventListener('click', function() {
		picker.show();
	});
}

// 遮罩

function mask(text) {
	$('.layer').remove();
	var content = '<div class="layer"> ' + text + '</div>'
	$('body').append(content);

	var w = $('.layer').width() + 40;
	var win = $(window).width();

	$('.layer').css('left', (win - w) / 2 + 'px');
	$('.layer').fadeIn();
	setTimeout(function() {
		$('.layer').fadeOut();
		$('.layer').remove();
	}, 2000);
}

// @param {string} img 图片的base64
// @param {int} dir exif获取的方向信息
// @param {function} next 回调方法，返回校正方向后的base64
function getImgData(img, dir, next) {
	var image = new Image();
	image.onload = function() {
		var degree = 0,
			drawWidth, drawHeight, width, height;
		drawWidth = this.naturalWidth;
		drawHeight = this.naturalHeight;
		var canvas = document.createElement('canvas');
		var context = canvas.getContext('2d');
		//以下改变一下图片大小
		var cw = image.width;
		var ch = image.height;
		var w = image.width;
		var h = image.height;
		canvas.width = w;
		canvas.height = h;
		if(cw > 1024 && cw > ch) {
			w = 1024;
			h = (1024 * ch) / cw;
			canvas.width = w;
			canvas.height = h;
		}
		if(ch > 1024 && ch > cw) {
			h = 1024;
			w = (1024 * cw) / ch;
			canvas.width = w;
			canvas.height = h;

		}

		//使用canvas旋转校正
		context.rotate(degree * Math.PI / 180);
		context.drawImage(this, 0, 0, w, h);
		//返回校正图片
		next(canvas.toDataURL("image/jpeg", .8));
	}
	image.src = img;
}
//// @param {string} img 图片的base64
//// @param {int} dir exif获取的方向信息
//// @param {function} next 回调方法，返回校正方向后的base64
//function getImgData(img, dir, next) {
//	var image = new Image();
//	image.onload = function() {
//		var degree = 0,
//			drawWidth, drawHeight, width, height;
//		drawWidth = this.naturalWidth;
//		drawHeight = this.naturalHeight;
//		//以下改变一下图片大小
////		var maxSide = Math.max(drawWidth, drawHeight);
////		if(maxSide > 1024) {
////			var minSide = Math.min(drawWidth, drawHeight);
////			minSide = minSide / maxSide * 1024;
////			maxSide = 1024;
////			if(drawWidth > drawHeight) {
////				drawWidth = maxSide;
////				drawHeight = minSide;
////			} else {
////				drawWidth = minSide;
////				drawHeight = maxSide;
////			}
////		}
//		var canvas = document.createElement('canvas');
//		console.log(canvas);
//		canvas.width = width = drawWidth;
//		canvas.height = height = drawHeight;
//		
//		var context = canvas.getContext('2d');
//		//判断图片方向，重置canvas大小，确定旋转角度，iphone默认的是home键在右方的横屏拍摄方式
//		switch(dir) {
//			//iphone横屏拍摄，此时home键在左侧
//			case 3:
//				degree = 180;
//				drawWidth = -width;
//				drawHeight = -height;
//				break;
//				//iphone竖屏拍摄，此时home键在下方(正常拿手机的方向)
//			case 6:
//				canvas.width = height;
//				canvas.height = width;
//				degree = 90;
//				drawWidth = width;
//				drawHeight = -height;
//				break;
//				//iphone竖屏拍摄，此时home键在上方
//			case 8:
//				canvas.width = height;
//				canvas.height = width;
//				degree = 270;
//				drawWidth = -width;
//				drawHeight = height;
//				break;
//		}
//		
//		//使用canvas旋转校正
//		context.rotate(degree * Math.PI / 180);
////		var ratio = getPixelRatio(context);
//		context.drawImage(this, 0, 0, drawWidth, drawHeight);
////		context.drawImage(this, 0, 0, drawWidth * ratio, drawHeight * ratio);
//		//返回校正图片
//		next(canvas.toDataURL("image/jpeg",0.8));
//		
//	
//		
//	}
//	image.src = img;
//}
//
//var getPixelRatio = function(context) {
//var backingStore = context.backingStorePixelRatio ||
//  context.webkitBackingStorePixelRatio ||
//  context.mozBackingStorePixelRatio ||
//  context.msBackingStorePixelRatio ||
//  context.oBackingStorePixelRatio ||
//  context.backingStorePixelRatio || 1;
// return (window.devicePixelRatio || 1) / backingStore;
//};
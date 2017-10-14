<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxe5e8f70efc47d1b8", "ecb3d8ca57dc4f4ff24c4a3e1b0ed702");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html class="height">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>联创世华</title>
		<link rel="stylesheet" href="../assets/css/amazeui.css">
		<link rel="stylesheet" href="../assets/css/common.css" />
		<link rel="stylesheet" href="../assets/css/style.css">

	</head>
   
	<body class="height">
		<header data-am-widget="header" class="am-header am-header-fixed index-header">
			<div class="am-header-left am-header-nav">
				<a href="javascript:history.back()" class="">
					<i class="arrow-left"></i>
				</a>
			</div>
			<h1 class="am-header-title">
		        <a href="javascript:;" class="">
		           申论批改
		        </a>
		    </h1>
			<div class="am-header-right am-header-nav">
				<a href="javascript:;" class="">
					<i class="compltet" style="color:#fff">完成</i>
				</a>
			</div>
		</header>
		<div class="sl-container">
			<div class="detail-wrapper clearfix" style="min-height: 60%;">
				<div class="detail-main">
					<div class="text">
						<textarea name="" rows="4" cols="" class="message" placeholder="请输入文字"></textarea>
						<div class="serve">保存</div>
					</div>
					<div class="ar-addimg">
						<div class="infor">
							已添加<span class="have"></span>张，还可添加<span class="addhave"></span>张
						</div>
						<div class="upload-wrap">
							<ul class="upload am-cf">
								<li class="upload-btn">
									<img src="../assets/i/add-img.png">
									<input type="file" class="j-file-cert" name="temp_file" />
								</li>
							</ul>
						</div>
					</div>
					<div class="voice-word">
						<!--<div class="voice" id="play" style="display: none;"></div>-->
					</div>
				</div>
			</div>
			<div class="voice-wrapper">
				<div class="voice1" id="sbtn">开始</br>说话</div>
				<div class="voice2" id="dbtn">停止</br>说话</div>
				<div class="voice3">点击</br>发送</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/amazeui.js"></script>
	<script type="text/javascript" src="../assets/js/template.js"></script>
	<script type="text/javascript" src="../assets/js/exif.min.js"></script>
	<script type="text/javascript" src="../assets/js/common.js"></script>
	<script type="text/javascript" src="../assets/js/iscroll.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
	<script type="text/javascript">
		$(function() {
			var id = GetQueryString('id');
			var token = getCookie('token');
			var imgArr = [];
			var para = window.location.search;
			$('.serve').on('click',function(){
				var txt = $('.message').val();
				setCookie('word',txt,1);
				mask('保存成功');
			});
			var word = getCookie('word');
			if(word != ""){
				$('.message').val(word);
			}
			
			//获取微信签名等一系列参数
			
			wx.config({
				debug: false,
				//appId: de.appId,
				 appId: '<?php echo $signPackage["appId"];?>',
                 timestamp: '<?php echo $signPackage["timestamp"];?>',
                 nonceStr: '<?php echo $signPackage["nonceStr"];?>',
                signature: '<?php echo $signPackage["signature"];?>',
				jsApiList: [
					"onMenuShareTimeline",
					"onMenuShareAppMessage",
					"onMenuShareQQ",
					"onMenuShareWeibo",
					"onMenuShareQZone",
					"startRecord",
					"stopRecord",
					"onVoiceRecordEnd",
					"playVoice",
					"pauseVoice",
					"stopVoice",
					"onVoicePlayEnd",
					"uploadVoice",
					"downloadVoice",
					"chooseImage",
					"previewImage",
					"uploadImage",
					"downloadImage",
					"translateVoice",
					"getNetworkType",
					"openLocation",
					"getLocation",
					"hideOptionMenu",
					"showOptionMenu",
					"hideMenuItems",
					"showMenuItems",
					"hideAllNonBaseMenuItem",
					"showAllNonBaseMenuItem",
					"closeWindow",
					"scanQRCode",
					"chooseWXPay",
					"openProductSpecificView",
					"addCard",
					"chooseCard",
					"openCard"
				]
			});
			wx.ready(function() {
				var START;
				var END;
				var recordTimer;
//				var voice = {
//					localId: ""
//				}
				var localID = [];
				sbtn.onclick = function(event) {
					event.preventDefault();
					START = new Date().getTime();

					recordTimer = setTimeout(function() {
						wx.startRecord({
							success: function() {
								localStorage.rainAllowRecord = 'true';
							},
							cancel: function() {
								alert('用户拒绝授权录音');
							}
						});
					}, 300);
				}
				dbtn.onclick = function(evevt) {
					event.preventDefault();
					END = new Date().getTime();
					if((END - START) < 300) {
						END = 0;
						START = 0;
						//小于300ms，不录音
						clearTimeout(recordTimer);
					} else {
						wx.stopRecord({
							success: function(res) {
								localID.push(res.localId);
								//播放语音
								var item = "";
								for(var i = 0;i<localID.length;i++){
									item +="<div class=\"voice\" id=\"play\"></div>";
								}
								$(".voice-word").html(item);
								var play = $('.voice-word .voice');
								play.each(function(i,ele){
									$(this).click(function(){
										var index = $(this).index();
										wx.playVoice({
											localId:localID[i] // 需要播放的音频的本地ID，由stopRecord接口获得
										});
									});
								});
							},
							fail: function(res) {
								alert(JSON.stringify(res));
							}
						});
					}

				};
				
				
				
//				play.onclick = function(event) {
//					event.preventDefault();
//					wx.playVoice({
//						localId: voice.localId // 需要播放的音频的本地ID，由stopRecord接口获得
//					});
//				}
				//注册微信播放录音结束事件【一定要放在wx.ready函数内】
				wx.onVoicePlayEnd({
					success: function(res) {
						stopWave();
					}
				});
				//上传图片
				var count = 0;
				$('.j-file-cert').on('change', function(e) {
					count++;
					if(count < 5) {
						var that = $(this);
						if(!window.FileReader) return;
	
						e.stopPropagation();
						e.preventDefault();
	
						var file = e.target.files[0];
						var content = '';
	
						if(!file.type.match('image.*')) {
							alert('文件' + f.name + '不是图片')
							return;
						}
	
						var orientation;
						//EXIF js 可以读取图片的元信息 https://github.com/exif-js/exif-js
						EXIF.getData(file, function() {
							orientation = EXIF.getTag(this, 'Orientation');
						});
	
						var reader = new FileReader();
	
						reader.onload = function(e) {
							getImgData(this.result, orientation, function(data) {
								$.ajax({
									type: 'POST',
									url: reqUrl("web_file_upload_return_url"),
									data: {
										token: token,
										keytype: 3,
										img: data
									},
									dataType: 'JSON',
									xhrFields: {
										withCredentials: true
									},
									async: false,
									success: function(data) {
										if(data.error_code == 100) {
											window.location.href = preUrl('log/login.html' + para + '&path=index/sl-correct.php');
										} else if(data.success){
											imgArr.push(data.infor[0].item1);
													content = '<li>' +
														'<img class="j-image" src="' + data.infor[0].item1 + '">' +
														'<i class="icon-close"></i>' +
														'</li>'
				
													that.parent().before(content);
													//	删除上传图片
													$('.upload').on('click', '.icon-close', function() {
														$(this).parent().remove();
														var tue = $(this).siblings().attr('src');
														imgArr.removeByValue(tue);
														if(count > 0) {
															count--;
														}
														var length = $('.upload').children('li').length;
														console.log(length);
														$('.have').text(length - 1);
														$('.addhave').text(4 - $('.have').text());
													});
													var length = $('.upload').children('li').length;
													console.log(length);
													$('.have').text(length - 1);
													$('.addhave').text(4 - $('.have').text());
				
										}
										
									}
								});
							});
	
						}
						reader.readAsDataURL(file);
					} else {
						mask('最多传4张图片');
						count = 4;
						return false;
					}
	
				});
				$('.compltet').click(function() {
					var message = $('.message').val();
					var imgString = imgArr.join(",");
					var localString = localID.join(",");
					$.ajax({
						url: reqUrl('pigai_do'),
						type: 'post',
						dataType: 'json',
						data: {
							token: token,
							pigai_id: id,
							pigai: imgString,
							pigai_word: message,
							pigai_sound:localString
						},
						xhrFields: {
							withCredentials: true
						},
						success: function(data) {
							if(data.error_code == 100) {
								window.location.href = preUrl('log/login.html' + para + '&path=index/sl-correct.php');
							} else if(data.success) {
								//							mask('ok')
								window.location.href = document.referrer;
							} else {
								mask(data.msg);
							}
	
						},
						error: function(e, request, settings) {
							alert(settings);
						}
					});
	
				})
	
				//删除数组中指定的一项
				Array.prototype.removeByValue = function(val) {
					for(var i = 0; i < this.length; i++) {
						if(this[i] == val) {
							this.splice(i, 1);
							break;
						}
					}
				}

			});
		});
	</script>

</html>
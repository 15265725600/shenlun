<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxe5e8f70efc47d1b8", "ecb3d8ca57dc4f4ff24c4a3e1b0ed702");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>

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

	<body>
		<header data-am-widget="header" class="am-header am-header-fixed index-header">
			<div class="am-header-left am-header-nav">
				<a href="javascript:history.back()" class="">
					<i class="arrow-left"></i>
				</a>
			</div>
			<h1 class="am-header-title">
		        <a href="javascript:;" class="">
		           批改详情
		        </a>
		    </h1>
		</header>
		<div class="cd-container">
			<script type="text/html" id="content">
				{{if infor[0].pigai_word != ""}}
				<div class="message">
					<p>{{infor[0].pigai_word}}</p>
				</div>
				{{/if}}
				{{if infor[0].pigai != null}}
				<div class="cd-img">
					<ul class="cd-list am-gallery" data-am-widget="gallery" data-am-gallery="{ pureview: true }">
						{{each infor[0].pigai.split(',') as img index}}
						<li class="list-item">
							<img src="{{img}}" alt="" />
							<i class="icon-close"></i>
						</li>
						{{/each}}
					</ul>
				</div>
				{{/if}}
				{{if infor[0].pigai_sound != null}}
				<div class="voice-word">
					{{each infor[0].pigai_sound.split(',') as item index}}
					<div class="voice" id = "play" style="padding: 5px 10px;display: block;">
					</div>
					{{/each}}
				</div>
				{{/if}}
			</script>
		</div>
	</body>
	<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/amazeui.js"></script>
	<script type="text/javascript" src="../assets/js/template.js"></script>
	<script type="text/javascript" src="../assets/js/common.js"></script>
	<script type="text/javascript" src="../assets/js/hidpi-canvas.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
	<script type="text/javascript">
		$(function() {
			
			//获取微信签名等一系列参数
			
					wx.config({
						debug: false,
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
						var arrstring = "";
						var para = window.location.search;
						var token = getCookie('token');
						var id = GetQueryString('id');
						$.ajax({
							url: reqUrl('pigai_detail'),
							type: 'post',
							dataType: 'json',
							data: {
								token: token,
								id: id
							},
							xhrFields: {
								withCredentials: true
							},
							success: function(data) {
								if(data.error_code == 100) {
									window.location.href = preUrl('log/login.html' + para + '&path=my/correct-details.php');
								} else if(data.success) {
									var content = template('content', data);
									$('.cd-container').html(content);
									$('.am-gallery').pureview();
									arrstring = data.infor[0].pigai_sound;
									var arrLen = arrstring.split(",");
									var play = $('.voice-word .voice');
									play.each(function(i,ele){
										$(this).click(function(){
											var index = $(this).index();
											wx.playVoice({
												localId:arrLen[i] // 需要播放的音频的本地ID，由stopRecord接口获得
											});
										});
									});
									
//									//播放语音
//									play.onclick = function(event) {
//										event.preventDefault();
//										wx.playVoice({
//											localId:voice.localId // 需要播放的音频的本地ID，由stopRecord接口获得
//										});
//										$('.tip').hide();
//									}
									//注册微信播放录音结束事件【一定要放在wx.ready函数内】
									wx.onVoicePlayEnd({
										success: function(res) {
											stopWave();
										}
									});
								} else {
									mask(data.msg);
								}

							},
							error: function(e, request, settings) {
								alert(settings);
							}
						});
					});
		});
	</script>

</html>
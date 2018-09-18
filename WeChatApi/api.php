<?php
define("TOKEN", "weixin");
include dirname(__FILE__)."/media.php";
include dirname(__FILE__)."/Lib/WeChatApi.class.php";
include dirname(__FILE__)."/Lib/WeChat.class.php";
class WxApi extends Wechat
{

	public function responseMsg(){
		parent::responseMsg();
		$this -> reText('您好,欢迎来到微信开发世界!');
	}
}
$WxApi = new WxApi();
$WxApi ->valid();
$WxApi ->responseMsg();


<?php
class WeChatApi
{
	//appID
	const appID = 'wxc85df9b030d8257d';
	//appsecret
	const appsecret = '4cff03fcd95db23489374da257253c37';
	//微信api接口链接
	public static function getApiUrl($name){
		$url = array(
			//api凭证access_token接口
			'api_access_token'=>"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appID."&secret=".self::appsecret,
			//客服消息发送接口
			'api_customer_send'=>"https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=",
			//创建菜单接口
			'api_create_menus' => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=",
			//使用code换取网页授权信息接口(获取openid和网页access_token)
			'api_get_access_info' => "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appID."&secret=".self::appsecret."&grant_type=authorization_code&code=",
			//群发消息接口
			'api_send_mass'=>'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=',
			//媒体上传接口
			'api_upload_media'=>'https://api.weixin.qq.com/cgi-bin/media/upload?type=image&access_token=',
			//授权验证接口
			'web_access_auth'=>'https://api.weixin.qq.com/sns/auth?',

		);
		return $url[$name];
	}
	//消息回复模板
	public static function getMsgTpl($type){
			$tpl = array(
				//文本回复模板
				"text" =>  "<xml>
					      <ToUserName><![CDATA[%s]]></ToUserName>
					     <FromUserName><![CDATA[%s]]></FromUserName>
					     <CreateTime>%s</CreateTime>
					     <MsgType><![CDATA[%s]]></MsgType>
					     <Content><![CDATA[%s]]></Content>
					     <FuncFlag>0</FuncFlag>
					      </xml>",
				//图片回复模板	      
             	"image" => "<xml>
				         <ToUserName><![CDATA[%s]]></ToUserName>
				         <FromUserName><![CDATA[%s]]></FromUserName>
				         <CreateTime>%s</CreateTime>
				        <MsgType><![CDATA[%s]]></MsgType>
				       <Image>
				      <MediaId><![CDATA[%s]]></MediaId>
				      </Image>
	                  </xml>",

			   //音乐的回复模板
	           "music"=>"<xml>
			    <ToUserName><![CDATA[%s]]></ToUserName>
			    <FromUserName><![CDATA[%s]]></FromUserName>
			    <CreateTime>%s</CreateTime>
			    <MsgType><![CDATA[%s]]></MsgType>
			    <Music>
			    <Title><![CDATA[%s]]></Title>
			    <Description><![CDATA[%s]]></Description>
			    <MusicUrl><![CDATA[%s]]></MusicUrl>
			    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			    </Music>
			    </xml>",

		        //定义图文模板
		        "news" => "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<ArticleCount>%s</ArticleCount>
				<Articles>
				 %s
				</Articles>
				</xml>"
			);
			return $tpl[$type];
	}
	//调试工具
	public static function debugTrace($filename,$data){
		file_put_contents($filename, $data);
	}

}
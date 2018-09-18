<?php
//微信的核心类
class WeChat
{
	//客户端的openId
	protected $fromUsername;
	//服务器的id
	protected $toUsername;
	//客户端上传的信息
	protected $keyword;
	//客户端上传的类型
	protected $sendType;
	//订阅类型或者菜单CLICK事件推送
	protected $Event;
	//菜单事件推送的EventKey
	protected $EventKey;
	//语音内容
	protected $Recognition;
	//Unix时间戳
	protected $time;
	//Curl方法
	public function CurlRequest($url,$data=null){
		//请自定义curl的请求方法,否则微信api无法使用			
	}
	//access_token方法
	public function GetAccessToken(){
		//请自定义access_token的获取,否则api没有调用的权限
	}
	//自动回复(此方法必须覆盖)
	public function responseMsg(){
		//接收微信客户端传入的数据
		$dataFromClient = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($dataFromClient)){
			//微信客户端有传入数据将其转为xml形式接收
			$postObj = simplexml_load_string($dataFromClient, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this -> fromUsername = $postObj->FromUserName;
            $this -> toUsername = $postObj->ToUserName;
            $this -> keyword = trim($postObj->Content);
            $this -> sendType = trim($postObj->MsgType);
            $this -> Event = trim($postObj->MsgType)=='event' ? $postObj->Event : '';
            $this -> Recognition = trim($postObj->MsgType)=='voice' ? $postObj->Recognition : '语音内容无法识别';
            $this -> EventKey = $postObj->Event=='CLICK' ? $postObj->EventKey : '';
            $this -> time = time();
		}
	}
	//文本回复接口
	protected function reText( $contentStr ){
		$resultStr = sprintf(WeChatApi::getMsgTpl('text'), $this->fromUsername, $this->toUsername, $this->time, 'text', $contentStr);
		echo $resultStr;	
	}
	//图片回复接口
	protected function reImage( $MediaId ){
		$resultStr = sprintf(WeChatApi::getMsgTpl('image'), $this->fromUsername, $this->toUsername, $this->time, 'image', $MediaId );
		echo $resultStr;
	}
	//音乐回复接口
	protected function reMusic( $title,$desc,$url,$hqurl ){
		$resultStr = sprintf(WeChatApi::getMsgTpl('music'), $this->fromUsername, $this->toUsername, $this->time, 'music', $title, $desc, $url, $hqurl);
        echo $resultStr;
	}
	//图文接口(可实现单图文和多图文)
	protected function reNews($items){
		$count = count( $items );
		$item = $this -> createNewsItems($items);
		$resultStr = sprintf(WeChatApi::getMsgTpl('news'), $this->fromUsername, $this->toUsername, $this->time, 'news', $count,$item);
        echo $resultStr;
	}
	/***
	图文消息生成接口,items必填参数:
	Title:标题
	Desc :描述
	PicUrl:图片连接
	Url:图文详细连接地址
	**/
	private function createNewsItems($items){
		foreach ($items as $data ) {
			$item .= "<item>
			<Title><![CDATA[{$data['Title']}]]></Title> 
			<Description><![CDATA[{$data['Desc']}]]></Description>
			<PicUrl><![CDATA[{$data['PicUrl']}]]></PicUrl>
			<Url><![CDATA[{$data['Url']}]]></Url>
			</item>";			
		}
		return $item;
	}
	//订阅关注回复
	protected function reSubscribe( $contentStr ){
		$this -> reText( $contentStr );
	}
	//绑定api时需要验证签名信息
    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
    }
    //客服回复接口
    protected function CustomerReText( $Text ){
    	$access_token = $this -> GetAccessToken();
    	$fromUsername = $this -> fromUsername;
    	$url = WeChatApi::getApiUrl('api_customer_send');
    	$url .= $access_token;
    	$content  = urlencode($Text);
        $data = array(
                "touser" => "{$fromUsername}" , //把数据设置还给微信客户端
                "msgtype"=>"text",//文本消息类型
                "text" => array(
                    "content"=> $content,//定义客服的文本消息回复内容
                ),
        );
        //由于发送消息接口只接收json数据，所以我们需要把数组转化为json字符
        $data = json_encode($data);
        //由于我们进行urlencode的编码，所以我们在传递的时候就需要解码
        $data = urldecode($data);
        //使用curl的post提交方式
        $this -> CurlRequest( $url , $data );
        exit();
    }
    /****
    客服回复图文信息接口(可实现多图文和单图文)
    $ImgText必须是数组,且含有以下索引:
	title:标题
	desc:描述
	url:内容详细链接
	picurl:图片链接
    ****/
    protected function CustomerReImgText( $ImgText ){
     	$access_token = $this -> GetAccessToken();
    	$fromUsername = $this -> fromUsername;
    	$url = WeChatApi::getApiUrl('api_customer_send');
    	$url .= $access_token; 
    	$set = array();
        foreach ($ImgText as $rs){
            $content = null;
            $content = array(
                "title"=>urlencode($rs['title']),
                "description"=>urlencode($rs['desc']),
                "url"=>$rs['url'],
                "picurl"=>$rs['picurl'],
            );          
            $set[] = $content;           
        }
        $data = array(
            "touser"=>"{$fromUsername}",
            "msgtype"=>"news",
            "news" => array(
                "articles" => $set,
            ),
        );
        //由于发送消息接口只接收json数据，所以我们需要把数组转化为json字符
        $data = json_encode($data);   
        $data = urldecode($data);    
        $this -> CurlRequest( $url , $data );
        exit(); //终止后面的代码进行运行         	
    }
    //使用code换取授权的openid和网页授权的access_token
    public function codeTransAccessInfo($code=null){
    	if( isset($code) ){
    		$url = WeChatApi::getApiUrl('api_get_access_info');
    		$url .= $code;
			$str = $this -> CurlRequest( $url );
			$access_info = json_decode($str,true);
			return $access_info;
    	}else{
			exit("Error:must TransCode.");
    	}
    }
    //群发信息接口
    public function SendMass($data){
    	$access_token = $this -> GetAccessToken();
    	$url = WeChatApi::getApiUrl('api_send_mass');
    	$url .= $access_token;
    	return $this -> CurlRequest( $url,$data );
    }
    //验证授权信息的有效性
    public function vailAccessInfo($openId,$web_access_token)
    {
		$url = WeChatApi::getApiUrl('web_access_auth');
		$url .= "access_token={$web_access_token}&openid={$openId}";
		$str = $this -> CurlRequest( $url );
		$validInfo = json_decode($str,true);
		return $validInfo;
    }
    //上传Media媒体接口
    public function UploadMedia($media_data){
    	$access_token = $this -> GetAccessToken();
    	$url = WeChatApi::getApiUrl('api_upload_media');
    	$url .= $access_token;
    	$data['media'] = $media_data;
    	//echo $data['media']."\n";
    	//echo $url;
    	return $this -> CurlRequest($url,$data);
    }
    //验证绑定
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
           echo $echoStr;
           exit;
        }
    }
}
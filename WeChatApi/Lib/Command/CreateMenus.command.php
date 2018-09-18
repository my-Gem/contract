 <?php
 include '../WeChatApi.class.php';
 include '../WeChat.class.php';
 $data = '';//请定义个性化菜单数据
 $WeChat = new WeChat();
 $access_token = $WeChat -> GetAccessToken();
 //获取api的url地址
 $url = WeChatApi::getApiUrl('api_create_menus');
 $url .= $access_token;
 $str = $WeChat -> CurlRequest( $url,$data );
 $json = json_decode($str);
 if( $json->errmsg == 'ok' ){
 	echo "Create Menus Successfully\n";
 }
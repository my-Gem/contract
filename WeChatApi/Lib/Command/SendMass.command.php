 <?php
@header("Content-type:text/html;charset=utf-8");
include '../WeChatApi.class.php';
include '../WeChat.class.php';
//toUser至少有两个或者以上参数
$data = '{
   "touser":[
    		"",
    		"",
    		""
   ],
    "msgtype": "text",
    "text": { "content": "Hello,EveryOne"}
}';
//请定义群发命令
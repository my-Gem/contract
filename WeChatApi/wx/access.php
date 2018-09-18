<?php
include "../Lib/WeChatApi.class.php";
include "../Lib/WeChat.class.php";
$WeChat = new WeChat();
$data = $WeChat -> codeTransAccessInfo($_GET['code']);
//$data['codeInfo'] = $_GET['code'];
//WeChatApi::debugTrace( "access_info.txt",var_export($data,true) );
$web_access_token = $data['access_token'];
$openId = $data['openid'];
$data['vaild'] = $WeChat -> vailAccessInfo($openId,$web_access_token);
WeChatApi::debugTrace( "access_info.txt",var_export($data,true) );
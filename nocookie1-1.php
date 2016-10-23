<?php

// session.use_trans_sid = 1修改服务器配置选项
// session.use_only_cookies = 0 修改为0
// 方式一:---------以下------------
  // session_start();
  // $_SESSION['var1']='这是第一条测试';
  // $url = "<a href='nousecookie.php'>下一页</a>";
  // echo $url;
//方式二:----------以下------------

session_start();
$_SESSION['var1']="这是第二条测试";
$sn=session_id();
$url="<a href='nousecookie.php?.\"$sn\".'>下一页</a>";
echo $url;
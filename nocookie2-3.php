<?php
       $fp=fopen("./nocookie2.txt","r");
       $sid=fread($fp,1024);
       fclose($fp);
       session_id($sid);
       session_start();
       if(isset($_SESSION['user']) && $_SESSION['user']="test") {
           echo "已登录!可以处理逻辑了";
           header("refresh:10;url=nocookie2-1.html");
       } else {
           //成功登录进行相关操作
           echo "未登录，无权访问";
           sleep(2);
           header("location:nocookie2-1.html");
           die();
       }
?>
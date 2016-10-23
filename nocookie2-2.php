<?php
       $name=$_POST['name'];
       $pass=$_POST['pass'];
       if(!$name || !$pass) {
           echo "用户名或密码为空，请<a href='./nocookie2-1.html'>重新登录</a>";
           header("location:nocookie2-1.html");
           die();
       }if(!($name=="test" && $pass=="test")){
          echo '还是不正确';
       }
       //注册用户
       ob_start();
       session_start();
       $_SESSION['user']= $name;
       $psid=session_id();
       $fp=fopen("./nocookie2.txt","w+");
       fwrite($fp,$psid);
       fclose($fp);
       echo "已登录<br>";
       echo "<a href='nocookie2-3.php'>下一页</a>";
?>

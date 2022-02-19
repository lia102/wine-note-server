<?php
$host = 'winenote.celb9pikiwhh.ap-northeast-2.rds.amazonaws.com';     //해당 서버의 ip주소를 입력
$user = 'admin';     //mysql 사용자의 이름을 입력  
$pw = 'qlalf1234';       //mysql 접속 비밀번호를 입력 
$dbName = 'winenote';       //데이터베이스의 이름을 입력
$server_ip = '13.209.70.82';
    
$conn = mysqli_connect($host , $user ,$pw,$dbName);
mysqli_query($conn,'SET NAMES utf8');
?>
<?php
include "../dbconn.php";

$response = array(); //retuen할 결과값
$email = $_POST["email"]; 
$pwd = $_POST["pwd"];
$hashPwd = password_hash($pwd, PASSWORD_DEFAULT);
$nickname = $_POST["nickname"];

function passwordCheck($_str){ //비밀번호 정규식 검사
    $pw = $_str;
    $num = preg_match('/[0-9]/u', $pw);
    $eng = preg_match('/[a-z]/u', $pw);
    $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);
 
    if(strlen($pw) < 8 ){
        return array(false,"비밀번호는 영문, 숫자, 특수문자를 혼합하여 최소 8자리 이상으로 입력해주세요.");
        exit;
    }
 
    if(preg_match("/\s/u", $pw) == true){
        return array(false, "비밀번호는 공백없이 입력해주세요.");
        exit;
    }
 
    if( $num == 0 || $eng == 0 || $spe == 0){
        return array(false, "영문, 숫자, 특수문자를 혼합하여 입력해주세요.");
        exit;
    }
 
    return array(true);
}

$pwdCheck = passwordCheck($pwd);
if($pwdCheck[0] ==false){
    $response["success"] = false; 
    $response["alert"] = $pwdCheck[1];
}else{
    $sql = "SELECT * FROM user WHERE nickname = '$nickname'";
    $res = mysqli_query($conn,$sql);
    $row = mysqlI_num_rows($res);

    if($row>0){
        $response["success"] = false; 
        $response["alert"] = "이미 사용중인 닉네임입니다.";
    }else{
        $sql = "INSERT INTO user (email,pwd,nickname,date) VALUES ('$email','$hashPwd','$nickname',now())";
        $res = mysqli_query($conn,$sql);
        $response["success"] = true;
    }   
}

echo json_encode($response);


?>
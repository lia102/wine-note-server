<?php
include "../dbconn.php";

$response = array(); //retuen할 결과값
$email = $_POST["email"]; 
//비밀번호 찾기에서 변경시
$pwd = $_POST["pwd"];
$hashPwd = password_hash($pwd, PASSWORD_DEFAULT);
//마이페이지에서 비번 변경시
$nowPwd = $_POST["nowPwd"];
$newPwd = $_POST["newPwd"];
//비밀번호 정규식 검사
function passwordCheck($_str){ 
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

if($nowPwd == null){ //비번찾기에서 비번변경시
    $pwdCheck = passwordCheck($pwd);
    if($pwdCheck[0] ==false){
        $response["success"] = false; 
        $response["alert"] = $pwdCheck[1];
    }else{
        $sql = "UPDATE user SET pwd = '$hashPwd' WHERE email = '$email'";
        $res = mysqli_query($conn,$sql);
        $response["success"] = true;
    }   
    echo json_encode($response);
}else{ //마이페이지에서 비번변경시
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $res = mysqli_query($conn,$sql);
    $row = mysqlI_fetch_array($res);
    $pwdFromDb = $row['pwd'];
    if(password_verify($nowPwd,$pwdFromDb)){
        $pwdCheck = passwordCheck($newPwd);
        if($pwdCheck[0] == false){
            $response["success"] = false; 
            $response["alert"] = $pwdCheck[1];
        }else{
            $hashed = password_hash($newPwd, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET pwd = '$hashed' WHERE email = '$email'";
            $res = mysqli_query($conn,$sql);
            $response["success"] = true;
        }
    }else{
        $response["alert"] = "현재 비밀번호를 확인해 주세요.";
        $response["success"] = false;
    }
    echo json_encode($response);
}
?>
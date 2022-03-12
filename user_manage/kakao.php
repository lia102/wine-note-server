<?php
include "../dbconn.php";
$response = array();
$state = $_POST["state"];
switch($_POST["state"]){
    case 'isSignup' : //카카오 로그인으로 받아온 아이디로 회원가입 이력 있는지 없는지 확인
        $id = $_POST["id"];

        $sql = "SELECT * FROM user WHERE email = '$id' AND kakao = 'true'";
        $res = mysqli_query($conn,$sql);
        $num = mysqlI_num_rows($res);

        if($num == 1){
            $row = mysqlI_fetch_array($res);
            $response["success"] = true;
            $response["user_pk"] = $row['user_pk'];
            $response["nickname"] = $row['nickname'];
            $response["profile_photo"] = $row['profile_photo'];
            $response["description"] = $row['description'];
        }else{
            $response["success"] = false;
        }

        echo json_encode($response);
        break;

    case 'nickCheck' : //카카오 로그인으로 받아온 아이디로 회원가입 이력 있는지 없는지 확인
        $id = $_POST["id"];
        $nickname = $_POST["nickname"];
    
        $sql = "SELECT * FROM user WHERE nickname = '$nickname'";
        $res = mysqli_query($conn,$sql);
        $num = mysqlI_num_rows($res);
    
        if($num >= 1){
            $response["success"] = false;
        }else{
            $sqlSignin = "INSERT INTO user (email,nickname,date,kakao) VALUES ('$id','$nickname',now(),'true')";
            $resSignin = mysqli_query($conn,$sqlSignin);
            $response["success"] = true;

            $sqlGetPk = "SELECT * FROM user WHERE email = '$id' AND kakao = 'true'";
            $resGetPk = mysqli_query($conn,$sqlGetPk);
            $rowGetPk = mysqlI_fetch_array($resGetPk);
            $response["user_pk"] = $rowGetPk['user_pk'];
            $response["nickname"] = $rowGetPk['nickname'];
            $response["profile_photo"] = $rowGetPk['profile_photo'];
            $response["description"] = $rowGetPk['description'];
        }
    
        echo json_encode($response);
        break;
}
?>
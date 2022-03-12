<?php
include "../dbconn.php";

$email = $_POST["email"];
$pwd = $_POST["pwd"];
$response = array();

$sql = "SELECT * FROM user WHERE email = '$email'";
$res = mysqli_query($conn,$sql);
$row = mysqlI_fetch_array($res);
$emailFromDb = $row['email'];
$pwdFromDb = $row['pwd'];
$user_pk = $row['user_pk'];
$nickname = $row['nickname'];
$profile_photo = $row['profile_photo'];
$description = $row['description'];
if($emailFromDb = ""){
    $response["success"] = false;
}else{
    if(password_verify($pwd,$pwdFromDb)){
        $response["success"] = true;
        $response["user_pk"] = $user_pk;
        $response["nickname"] = $nickname;
        $response["profile_photo"] = $profile_photo;
        $response["description"] = $description;
    }else{
        $response["success"] = false;
    }
}
echo json_encode($response);
?>
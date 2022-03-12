<?php
include "../dbconn.php";
$response = array();
$email = $_POST["email"];
$sql = "SELECT * FROM user WHERE email = '$email'";
$res = mysqli_query($conn,$sql);
$row = mysqlI_fetch_array($res);
$id = $row['email'];
if($id==""){
    $response["idCheck"] = true;
}else{
    $response["idCheck"] = false;
}
echo json_encode($response);
?>
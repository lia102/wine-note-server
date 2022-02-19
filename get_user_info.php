<?php
include "./dbconn.php";
$response = array();

(int)$pkId = $_POST["pkId"];
(int)$otherUserPkId = $_POST["otherUserPkId"];
$response["success"] = true;

$sqlProfile = "SELECT * FROM user WHERE user_pk = '$otherUserPkId'";
$resProfile = mysqli_query($conn,$sqlProfile);
$rowProfile = mysqlI_fetch_array($resProfile);
$response["nickname"] = $rowProfile['nickname'];
$response["profile_photo"] = $rowProfile['profile_photo'];
$response["description"] = $rowProfile['description'];

$sqlNote = "SELECT * FROM note  WHERE writer_pk = '$otherUserPkId'";
$sqlWishList = "SELECT * FROM wish_list  WHERE writer_pk = '$otherUserPkId'";
$resNote = mysqli_query($conn,$sqlNote);
$resWishList = mysqli_query($conn,$sqlWishList);
$response["note_cnt"] = mysqli_num_rows($resNote);
$response["wish_cnt"] = mysqli_num_rows($resWishList);

$sqlFollower = "SELECT * 
                FROM follow  
                WHERE followee = '$otherUserPkId'";
$sqlFollowee = "SELECT * 
                FROM follow  
                WHERE follower = '$otherUserPkId'";
$resFollower = mysqli_query($conn,$sqlFollower);
$resFollowee = mysqli_query($conn,$sqlFollowee);
$response["follower_cnt"] = mysqli_num_rows($resFollower);
$response["followee_cnt"] = mysqli_num_rows($resFollowee);

$sqlIsFollow = "SELECT * FROM follow WHERE follower ='$pkId' AND followee = '$otherUserPkId'";
$resIsFollow = mysqli_query($conn,$sqlIsFollow );
if(mysqli_num_rows($resIsFollow)>=1){
    $response["isFollow"] = true;
}else{
    $response["isFollow"] = false;
}

echo json_encode($response);
?>
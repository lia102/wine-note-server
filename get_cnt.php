<?php
include "./dbconn.php";

$pkId = $_POST["pkId"];
$response = array();

$sqlNote = "SELECT * FROM note  WHERE writer_pk = '$pkId'"; //해당 유저가 작성한 테이스팅 노트 sql
$resNote = mysqli_query($conn,$sqlNote);
$rowNote = mysqlI_num_rows($resNote); //작성한 노트 갯수

$sqlWishList = "SELECT * FROM wish_list  WHERE writer_pk = '$pkId'"; //해당 유저의 위시리스트 sql
$resWishList = mysqli_query($conn,$sqlWishList);
$rowWishList = mysqlI_num_rows($resWishList); //위시리스트 갯수

$sqlFollower = "SELECT * 
                FROM follow  
                WHERE followee = '$pkId'"; //해당 유저를 팔로우 중인 팔로워 sql
$resFollower = mysqli_query($conn,$sqlFollower);

$sqlFollowee = "SELECT * 
                FROM follow  
                WHERE follower = '$pkId'"; //해당 유저가 팔로우 중인 유저 sql
$resFollowee = mysqli_query($conn,$sqlFollowee);


$response["success"] = true;
$response["note_cnt"] = $rowNote;
$response["wish_cnt"] = $rowWishList;
$response["follower_cnt"] = mysqli_num_rows($resFollower);
$response["followee_cnt"] = mysqli_num_rows($resFollowee);
    
echo json_encode($response);
?>
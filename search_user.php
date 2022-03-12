<?php
include "./dbconn.php";
$keyword = trim($_POST["keyword"]);
$response = array();

$sql = "SELECT * FROM user WHERE nickname LIKE '%$keyword%'";
$res = mysqli_query($conn,$sql);  
$row = mysqlI_num_rows($res);  
if($row >= 1){
    $rows = array();
    while($r = mysqli_fetch_assoc($res)) {
        $rows[] = $r;
    }
    $response["success"] = true;
    $response["rows"] = $rows;
}else{
    $response["success"] = false; 
}
echo json_encode($response);
?>
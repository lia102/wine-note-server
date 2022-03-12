<?php
include "../dbconn.php";
$response = array();

$pkId = $_POST["pkId"];
$nickname = $_POST["nickname"];
$description = $_POST["description"];
$img = $_POST["img"];
$date=time();

if (substr($img, 0, 4)=="http") {
    $pathForSql = $img;
}else{
    $file_path = "/var/www/html/img/".$pkId.$date.".jpg";
    // create a new empty file
    $myfile = fopen($file_path, "wb") or die("Unable to open file!");
    // add data to that file
    file_put_contents($file_path, base64_decode($img));
    $pathForSql = "http://".$server_ip."/img/".$pkId.$date.".jpg";
}
ini_set('memory_limit','-1');//이 PHP에 한해 메모리 무제한으로 풀기
 
$sql= "UPDATE user SET profile_photo = '$pathForSql', nickname = '$nickname', description = '$description' WHERE user_pk = $pkId";
$result = mysqli_query($conn, $sql);
if($result === false){
    $response["success"] = false;
}else{
    $response["success"] = true;
    $response["newImg"] = $pathForSql;
    $response["substr"] = substr($img, 0, 3);
}    
mysqli_close($conn);

echo json_encode($response);
?>
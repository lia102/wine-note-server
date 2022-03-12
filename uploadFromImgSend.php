<?php
include "./dbconn.php";
$response = array();

$mode = $_POST["mode"];
$pkId = $_POST["pkId"];
$otherPkId = $_POST["otherPkId"];
$img = $_POST["img"];
$date=time();

ini_set('memory_limit','-1');//이 PHP에 한해 메모리 무제한으로 풀기

if($mode == "one"){
    $file_path = "/var/www/html/img/".$pkId.$date.".jpg";
    // create a new empty file
    $myfile = fopen($file_path, "wb") or die("Unable to open file!");
        // add data to that file
    file_put_contents($file_path, base64_decode($img));
    $pathForSql = "http://".$server_ip."/img/".$pkId.$date.".jpg";
    $response["success"] = true;
    $response["link"] = $pathForSql;
}else{
    $imgList1 = str_replace("[","",$img);
    $imgList2 = str_replace("]","",$imgList1);
    $imgList_ar = explode(",", $imgList2);
    $links = [];
    for($i = 0; $i < sizeof($imgList_ar);$i++){
        $file_path = "/var/www/html/img/".$pkId.$date.$i.".jpg";
        // create a new empty file
        $myfile = fopen($file_path, "wb") or die("Unable to open file!");
            // add data to that file
        file_put_contents($file_path, base64_decode($imgList_ar[$i]));
        $pathForSql = "http://".$server_ip."/img/".$pkId.$date.$i.".jpg";
        $links[] = $pathForSql;
    }
    $response["success"] = true;
    $response["links"] = $links;
}

echo json_encode($response);


?>
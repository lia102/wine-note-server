<?php
include "../dbconn.php";
$idList = $_POST["idList"];
$response = array();

$idList1 = str_replace("[","",$idList);
$idList2 = str_replace("]","",$idList1);
$rows = array();
if(strpos($idList, ",") !== false) {  
    $idList_ar = explode(",", $idList2);
    for($i = 0; $i < sizeof($idList_ar);$i++){
        $sqlProfile = "SELECT * FROM user WHERE user_pk = '$idList_ar[$i]'";
        $resProfile = mysqli_query($conn,$sqlProfile);
        $rowProfile = mysqli_fetch_array($resProfile);
        array_push($response,[
            "pkId" => $idList_ar[$i]
            ,"nickname" => $rowProfile['nickname']
            ,"profilePhoto" =>  $rowProfile['profile_photo']
        ]);
    }
}else{
    $sqlProfile = "SELECT * FROM user WHERE user_pk = '$idList2'";
    $resProfile = mysqli_query($conn,$sqlProfile);
    $rowProfile = mysqli_fetch_array($resProfile);
    array_push($response,[
        "pkId" => $idList2
        ,"values" => $rowProfile['nickname']
        ,"profilePhoto" =>  $rowProfile['profile_photo']
    ]);
}

echo json_encode($response);
?>
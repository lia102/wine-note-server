<?php
include "./dbconn.php";
$mode = $_POST["mode"];
$response = array();
$date=time();

switch($mode){
    case "create":
        (int)$pkId = $_POST["pkId"];
        $wineName = $_POST["wineName"];
        $region = $_POST["region"];
        $style = $_POST["style"];
        $price = $_POST["price"];
        $img = $_POST["img"];
        $state = $_POST["state"];
        $link = $_POST["link"];


        $sql = "SELECT * FROM wish_list WHERE writer_pk ='$pkId' AND wine_name = '$wineName'";
            $res = mysqli_query($conn,$sql);
            $row = mysqlI_num_rows($res);
            if($row >= 1){
                if($state == 'false'){
                    $row = mysqlI_fetch_array($res);
                    $idx_wish = $row['idx_wish'];
                    $sql3 = "DELETE FROM wish_list WHERE idx_wish='$idx_wish'";
                    $res3 = mysqli_query($conn,$sql3);
                    if($res3){
                        $response["success"] = true;
                    }else{
                        $response["success"] = false;
                    }
                }else{
                    $response["success"] = true;
                }
            }else{
                if($state == 'true'){
                    $sql2 = "INSERT INTO wish_list 
                    (writer_pk,wine_name,wine_region,wine_style,wine_price,wine_img,date,link) 
                    VALUES ('$pkId','$wineName','$region','$style','$price','$img',now(),'$link')";
                    $res2 = mysqli_query($conn,$sql2);
                    if($res2){
                        $response["success"] = true;
                    }else{
                        $response["success"] = false;
                    }
                }else{
                    $response["success"] = true;  
                }   
            }
        echo json_encode($response);

        break;

        case "check":
            (int)$pkId = $_POST["pkId"];
            $wineName = $_POST["wineName"];
    
            $sql = "SELECT * FROM wish_list WHERE writer_pk ='$pkId' AND wine_name = '$wineName'";
            $res = mysqli_query($conn,$sql);
            $row = mysqlI_num_rows($res);
            if($row >= 1){
                $response["success"] = true;
            }else{
                $response["success"] = false;
            }
            echo json_encode($response);
    
            break;
        

    case "get_list":
        (int)$pkId = $_POST["pkId"];
        $sql = "SELECT * FROM wish_list WHERE writer_pk ='$pkId' ORDER BY idx_wish DESC";
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

        break;


    case "delete":
        $idx_wish_list = str_replace("[","",$_POST["idx_wish"]);
        $idx_wish_list2 = str_replace("]","",$idx_wish_list);
        $array = explode(",",$idx_wish_list2);
        for($i = 0; $i < sizeof($array);$i++){
            (int)$s = trim($array[$i]);
            $sql = "DELETE FROM wish_list WHERE idx_wish='$s'";
            $res = mysqli_query($conn,$sql);
        }
        $response["success"] = true;
        echo json_encode($response);
        break;


}
?>
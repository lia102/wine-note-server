<?php
include "./dbconn.php";

$mode = $_POST["mode"];
$response = array();
$date=time();

switch($mode){
    case "작성":
        $getUploadMode = $_POST["getUploadMode"];
        (int)$pkId = $_POST["pkId"];
        $name = $_POST["name"];
        $region = $_POST["region"];
        $price = $_POST["price"];
        $grapes = $_POST["grapes"];
        (int)$vintage = $_POST["vintage"];
        
        $comment = $_POST["comment"];
        (float)$rating = $_POST["rating"];
        (int)$acid = $_POST["acid"];
        (int)$sweet = $_POST["sweet"];
        (int)$body = $_POST["body"];
        (int)$tannin = $_POST["tannin"];

        if($getUploadMode == "direct"){
            $getImg = $_POST["img"];
            $file_path = "/var/www/html/img/".$pkId.$date.".jpg";
            // create a new empty file
            $myfile = fopen($file_path, "wb") or die("Unable to open file!");
                // add data to that file
            file_put_contents($file_path, base64_decode($getImg));
            $img = "http://".$server_ip."/img/".$pkId.$date.".jpg";
        }else{
            $img = $_POST["img"];
        }

        $sql = "INSERT INTO note 
        (writer_pk,wine_name,wine_region,wine_price,wine_grapes,wine_vintage,wine_img,intensity_body,intensity_acid,intensity_sweet,intensity_tannin,comment,rating,date) 
        VALUES ('$pkId','$name','$region','$price','$grapes','$vintage','$img','$body','$acid','$sweet','$tannin','$comment','$rating',now())";
        $res = mysqli_query($conn,$sql);
        if($res){
            $sql2 = "SELECT * FROM note  WHERE writer_pk = '$pkId' ORDER BY date DESC LIMIT 1";
            $res2 = mysqli_query($conn,$sql2);
            $row2 = mysqlI_fetch_array($res2);
            $idx_note = $row2['idx_note'];
            $response["success"] = true;
            $response["idx_note"] = $idx_note;
        }else{
            $response["success"] = false;
        }
        echo json_encode($response);
        break;
    
    case "리스트조회": 
        (int)$pkId = $_POST["pkId"];
        $sql = "SELECT * FROM note  WHERE writer_pk = '$pkId' ORDER BY date DESC";
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
    
    case "조회":
        (int)$idx_note = $_POST["idx_note"];
        $sql = "SELECT * 
        FROM note  
        LEFT JOIN user 
        ON note.writer_pk = user.user_pk 
        WHERE idx_note = '$idx_note'";
        $res = mysqli_query($conn,$sql);
        if($res){
            $response["success"] = true;
            $row = mysqlI_fetch_array($res);
            $response["nickname"] = $row['nickname'];
            $response["profile_photo"] = $row['profile_photo'];
            $response["writer_pk"] = $row['writer_pk'];
            $response["wine_name"] = $row['wine_name'];
            $response["wine_region"] = $row['wine_region'];
            $response["wine_price"] = $row['wine_price'];
            $response["wine_grapes"] = $row['wine_grapes'];
            $response["wine_vintage"] = $row['wine_vintage'];
            $response["wine_img"] = $row['wine_img'];
            $response["intensity_body"] = $row['intensity_body'];
            $response["intensity_acid"] = $row['intensity_acid'];
            $response["intensity_sweet"] = $row['intensity_sweet'];
            $response["intensity_tannin"] = $row['intensity_tannin'];
            $response["comment"] = $row['comment'];
            $response["rating"] = $row['rating'];
            $response["date"] = $row['date'];
        }else{
            $response["success"] = false; 
        }
        echo json_encode($response);
        break;

    case "수정":
        $changeImg = $_POST["changeImg"];
        (int)$idx_note = $_POST["idx_note"];
        $region = $_POST["region"];
        $price = $_POST["price"];
        $grapes = $_POST["grapes"];
        (int)$vintage = $_POST["vintage"];
        $comment = $_POST["comment"];
        (float)$rating = $_POST["rating"];
        (int)$acid = $_POST["acid"];
        (int)$sweet = $_POST["sweet"];
        (int)$body = $_POST["body"];
        (int)$tannin = $_POST["tannin"];

        if($changeImg == "true"){
            $getImg = $_POST["img"];
            $file_path = "/var/www/html/img/".$pkId.$date.".jpg";
            // create a new empty file
            $myfile = fopen($file_path, "wb") or die("Unable to open file!");
                // add data to that file
            file_put_contents($file_path, base64_decode($getImg));
            $img = "http://".$server_ip."/img/".$pkId.$date.".jpg";
        }else{
            $img = $_POST["img"];
        }

        $sql = "UPDATE note
         SET wine_region = '$region', wine_price = '$price', wine_grapes = '$grapes', wine_vintage = '$vintage', wine_img = '$img', 
        intensity_body = '$body',intensity_acid= '$acid',intensity_sweet = '$sweet',intensity_tannin = '$tannin',
        comment = '$comment',rating = '$rating' WHERE idx_note = $idx_note";

        $res = mysqli_query($conn,$sql);
        if($res){
            $response["success"] = true;
            $response["idx_note"] = $idx_note;
        }else{
            $response["success"] = false;
        }
        echo json_encode($response);

        break;


    case "삭제":
        (int)$idx_note = $_POST["idx_note"];
        $sql = "DELETE FROM note WHERE idx_note='$idx_note'";
        $res = mysqli_query($conn,$sql);
        if($res){
            $response["success"] = true;  
        }else{
            $response["success"] = false;
        }
        echo json_encode($response);
        break;

    case "getAllReview":
        $wineName = $_POST["wineName"];
        $sql = "SELECT user.user_pk,user.nickname,user.profile_photo,note.rating,note.comment,note.date,note.idx_note
            FROM note
            LEFT JOIN user 
            ON note.writer_pk = user.user_pk 
            WHERE wine_name = '$wineName'";
        $res = mysqli_query($conn,$sql);
        $allCnt = mysqlI_num_rows($res);
        $sqlCnt1 = "SELECT * FROM note WHERE 0 < rating AND rating < 2 AND wine_name = '$wineName' ";
        $sqlCnt2 = "SELECT * FROM note WHERE 2 <= rating AND rating < 3 AND wine_name = '$wineName' ";
        $sqlCnt3 = "SELECT * FROM note WHERE 3 <= rating AND rating < 4 AND wine_name = '$wineName' ";
        $sqlCnt4 = "SELECT * FROM note WHERE 4 <= rating AND rating < 5 AND wine_name = '$wineName' ";
        $sqlCnt5 = "SELECT * FROM note WHERE 5 <= rating  AND wine_name = '$wineName' ";
        $resCnt1 = mysqli_query($conn,$sqlCnt1);
        $resCnt2 = mysqli_query($conn,$sqlCnt2);
        $resCnt3 = mysqli_query($conn,$sqlCnt3);
        $resCnt4 = mysqli_query($conn,$sqlCnt4);
        $resCnt5 = mysqli_query($conn,$sqlCnt5);
        if($allCnt >= 1){
            $cnt1 = mysqlI_num_rows($resCnt1);
            $cnt2 = mysqlI_num_rows($resCnt2);
            $cnt3 = mysqlI_num_rows($resCnt3);
            $cnt4 = mysqlI_num_rows($resCnt4);
            $cnt5 = mysqlI_num_rows($resCnt5);
            $rows = array();
            while($r = mysqli_fetch_assoc($res)) {
                $rows[] = $r;
            }
            $response["success"] = true;
            $response["rows"] = $rows;
            $response["allCnt"] = $allCnt;
            $response["cnt1"] = $cnt1;
            $response["cnt2"] = $cnt2;
            $response["cnt3"] = $cnt3;
            $response["cnt4"] = $cnt4;
            $response["cnt5"] = $cnt5;
        }else{
            $response["success"] = false; 
        }
        echo json_encode($response);
        break;

    case "getRating":
        $wineName = $_POST["wineName"];
        $sql = "SELECT rating FROM note WHERE wine_name = '$wineName'";
        $res = mysqli_query($conn,$sql);
        $cnt = mysqlI_num_rows($res);
        if($cnt >= 1){
            $rows = array();
            while($r = mysqli_fetch_assoc($res)) {
                $rows[] = $r['rating'];
            }
            $sum = array_sum($rows);
            $ave = $sum/sizeof($rows);
            $response["success"] = true;
            $response["ave"] = $ave;
        }else{
            $response["success"] = false; 
        }
        echo json_encode($response);
        break;
}


?>
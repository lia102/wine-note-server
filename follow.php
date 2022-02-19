<?php
include "./dbconn.php";
$mode = $_POST["mode"];
$response = array();

switch($mode){
    case "follow":
        (int)$pkId = $_POST["pkId"];
        (int)$otherUserPkId = $_POST["otherUserPkId"];

        $sql = "INSERT INTO follow (follower,followee) VALUES ('$pkId','$otherUserPkId')";
        $res = mysqli_query($conn,$sql);
        if($res){
            $response["success"] = true;
        }else{
            $response["success"] = false;
        }
        echo json_encode($response);
        break;

    case "unfollow":
        (int)$pkId = $_POST["pkId"];
        (int)$otherUserPkId = $_POST["otherUserPkId"];

        $sql = "DELETE FROM follow WHERE follower='$pkId' AND followee='$otherUserPkId'";
        $res = mysqli_query($conn,$sql);
        if($res){
            $response["success"] = true;
        }else{
            $response["success"] = false;
        }
        echo json_encode($response);
        break;

    case "myFollowerList": //나를 팔로우하는 유저들
        (int)$pkId = $_POST["pkId"];
        $sql = "SELECT user.user_pk,user.nickname,user.profile_photo 
        FROM follow  
        LEFT JOIN user 
        ON follow.follower = user.user_pk 
        WHERE followee ='$pkId'";
        $res = mysqli_query($conn,$sql);
        $row = mysqlI_num_rows($res);

        $sql2 = "SELECT followee FROM follow WHERE follower = '$pkId'"; //내가 팔로우하는 유저들의 pk
        $res2 = mysqli_query($conn,$sql2);    //
        
        if($row >= 1){
            $rows = array();
            $rows2 = array();
            while($r = mysqli_fetch_assoc($res)) {
                $rows[] = $r;
            }
            while($r2 = mysqli_fetch_assoc($res2)) {
                $rows2[] = $r2['followee'];
            }
            $response["success"] = true;
            $response["rows"] = $rows;
            $response["rows2"] = $rows2;//작성 테스트
        }else{
            $response["success"] = false; 
        }
        echo json_encode($response);

        break;

        case "myFolloweeList": //내가 팔로우하는 유저들
            (int)$pkId = $_POST["pkId"];
            $sql = "SELECT user.user_pk,user.nickname,user.profile_photo 
            FROM follow  
            LEFT JOIN user 
            ON follow.followee = user.user_pk 
            WHERE follower ='$pkId'";
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


            case "otherFollowerList": //해당 유저를 팔로우하는 유저들
                (int)$pkId = $_POST["pkId"];
                (int)$otherUserPkId = $_POST["otherUserPkId"];
                $sql = "SELECT user.user_pk,user.nickname,user.profile_photo 
                FROM follow  
                LEFT JOIN user 
                ON follow.follower = user.user_pk 
                WHERE followee ='$otherUserPkId'";
                $res = mysqli_query($conn,$sql);
                $row = mysqlI_num_rows($res);
        
                $sql2 = "SELECT followee FROM follow WHERE follower = '$pkId'"; //내가 팔로우하는 유저들의 pk
                $res2 = mysqli_query($conn,$sql2);    
                
                if($row >= 1){
                    $rows = array();
                    $rows2 = array();
                    while($r = mysqli_fetch_assoc($res)) {
                        $rows[] = $r;
                    }
                    while($r2 = mysqli_fetch_assoc($res2)) {
                        $rows2[] = $r2['followee'];
                    }
                    $response["success"] = true;
                    $response["rows"] = $rows;
                    $response["rows2"] = $rows2;
                }else{
                    $response["success"] = false; 
                }
                echo json_encode($response);
        
                break;


                case "otherFolloweeList": //해당 유저가 팔로우하는 유저들
                    (int)$pkId = $_POST["pkId"];
                    (int)$otherUserPkId = $_POST["otherUserPkId"];
                    $sql = "SELECT user.user_pk,user.nickname,user.profile_photo 
                    FROM follow  
                    LEFT JOIN user 
                    ON follow.followee = user.user_pk 
                    WHERE follower ='$otherUserPkId'";

                    $sql2 = "SELECT followee FROM follow WHERE follower = '$pkId'"; //내가 팔로우하는 유저들의 pk
                    $res2 = mysqli_query($conn,$sql2);   

                    $res = mysqli_query($conn,$sql);
                    $row = mysqlI_num_rows($res);
                    if($row >= 1){
                        $rows = array();
                        $rows2 = array();
                        while($r = mysqli_fetch_assoc($res)) {
                            $rows[] = $r;
                        }
                        while($r2 = mysqli_fetch_assoc($res2)) {
                            $rows2[] = $r2['followee'];
                        }
                        $response["success"] = true;
                        $response["rows"] = $rows;
                        $response["rows2"] = $rows2;
                    }else{
                        $response["success"] = false; 
                    }
                    echo json_encode($response);
            
                    break;

    case "changeState":
        (int)$pkId = $_POST["pkId"];
            $falsePkId = str_replace("[","",$_POST["falsePkId"]);
            $falsePkId2 = str_replace("]","",$falsePkId);
            $falsePkIdArray = explode(",",$falsePkId2);
            for($i = 0; $i < sizeof($falsePkIdArray);$i++){
                (int)$s = trim($falsePkIdArray[$i]);
                $sql = "DELETE FROM follow WHERE follower = '$pkId' AND followee ='$s'";
                $res = mysqli_query($conn,$sql);
            }
            $truePkId = str_replace("[","",$_POST["truePkId"]);
            $truePkId2 = str_replace("]","",$truePkId);
            $truePkIdArray = explode(",",$truePkId2);
           
            for($i = 0; $i < sizeof($truePkIdArray);$i++){
                (int)$s = trim($truePkIdArray[$i]);
                $checkSql = "SELECT * FROM follow WHERE follower ='$pkId' AND followee ='$s'";
                $checkRes = mysqli_query($conn,$checkSql);
                $CheckRow = mysqlI_num_rows($checkRes);
                if($CheckRow == 0){
                    if($s!=0){
                        $sql2 = "INSERT INTO follow (follower,followee) VALUES ('$pkId','$s')";
                        $res2 = mysqli_query($conn,$sql2);
                    }
                }
            }
        $response["success"] = true;
        echo json_encode($response);
        break;


}
?>
<?php
        
    require_once('inc/db.php');
  
    $idx = $_POST['idx'];
    $passwd = $_POST['passwd'];

    
    $sql = "SELECT * FROM qna_reply WHERE idx = $idx";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_object($result);

    $hash = $data->password;
    // echo json_encode([ 'status'=> $sql]);

    if(password_verify($passwd, $hash)){
        //비번이 일치하면
        //해당 댓글 삭제
        $sql = "DELETE FROM qna_reply where idx = $idx";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo json_encode([ 'status'=>'success']);
        }else{
            echo json_encode(['status'=>'deletefail']);
        }
    }else{
        //일치하지 않으면
        echo json_encode(['status'=>'PWerror']);
    }


























?>
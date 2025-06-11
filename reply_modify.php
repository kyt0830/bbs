<?php
        
    require_once('inc/db.php');
  
    $idx = $_POST['idx'];
    $passwd = $_POST['passwd'];
    $content = $_POST['content'];
    $sql = "SELECT * FROM qna_reply WHERE idx = $idx";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_object($result);
    $hash = $data->password;
    
    if(password_verify($passwd, $hash)){
    //     //비번이 일치하면 수정
        $sql = "UPDATE qna_reply SET content = '{$content}' WHERE idx = $idx";
        $result = mysqli_query($conn, $sql);
        if($result){
            echo json_encode([ 'status'=>'success']);
        }else{
            echo json_encode(['status'=>'modifyfail']);
        }
    }else{
        // 일치하지 않으면
        echo json_encode(['status'=>'PWerror']);
    }

?>
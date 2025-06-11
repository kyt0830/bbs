<?php
        
    require_once('inc/db.php');
  
    $idx = $_POST['idx'];
    $sql = "DELETE FROM qna_board where idx = $idx";
    $result = mysqli_query($conn, $sql);

    

    if($result){
        echo json_encode([ 'status'=>'success']);
    }else{
        echo json_encode(['status'=>'fail']);
    }











?>
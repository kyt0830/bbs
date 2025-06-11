<?php
        
    require_once('inc/db.php');
  
    $idx = $_POST['idx'];
    $password = $_POST['pwd'];

    $sql = "SELECT * FROM qna_board WHERE idx = $idx";
  
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_object($result);

 
    $hash = $data->password;
    if(password_verify($password, $hash)){
        //비번이 일치하면
        echo json_encode([
            'status'=>'success',
            'username'=>$data->username,
            'date'=>$data->date,
            'subject'=>$data->subject,
            'message'=>$data->message
       
        ]);
    }else{
        //일치하지 않으면
        echo json_encode(['status'=>'fail']);
    }











?>
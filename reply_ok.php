<?php
    require_once('inc/db.php');

    $bid = $_POST['bid'];
    $name = $_POST['name'];
    $content = $_POST['content'];

    $repassword = !empty($_POST['repassword']) ? password_hash($_POST['repassword'], PASSWORD_DEFAULT) : '';
  

    $sql = "INSERT INTO qna_reply (
        bid, 
        name,
        content,
        password
        ) VALUES(
            $bid,
            '${name}',
            '${content}',
            '${repassword}'
    )";
    
    print_r($sql);
    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<script>
            alert('댓글 입력 완료');
            location.href ='index.php';
        </script>";
    }else{
        echo "<script>
            alert('댓글 입력 실패!');
        </script>";
    }






?>
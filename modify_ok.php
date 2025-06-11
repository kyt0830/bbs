<?php
    require_once('inc/db.php');


    $idx = $_POST['idx'];
    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $message = $_POST['message'];

    // update 테이블명 set 컬럼명1='수정된 값', 컬럼명2='수정된 값' where 조건;

    $sql = "UPDATE qna_board SET 
    subject = '{$subject}',
    username = '{$username}',
    message = '{$message}'
    WHERE idx = $idx";


    print_r($sql);
    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<script>
            alert('글 입력 완료!');
            location.href ='index.php';
        </script>";
    }else{
        echo "<script>
            alert('글 입력 실패!');
            history.back();
        </script>";
    }

?>
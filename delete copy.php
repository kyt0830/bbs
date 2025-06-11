<?php
    require_once('inc/db.php');

    $idx = $GET['idx'];
    
    $sql = "DELETE FROM qna_board where idx = $idx";
 
    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<script>
            alert('글 삭제 완료!');
            location.href ='index.php';
        </script>";
    }else{
        echo "<script>
            alert('글 삭제제 실패!');
            history.back();
        </script>";
    }






?>
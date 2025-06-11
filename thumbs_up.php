<?php
  require('inc/db.php');


  
$getIdx = $_GET['idx'];
$sql = "SELECT * FROM qna_board WHERE idx = $getIdx";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_object($result);

$thumbs = $data->likes + 1;
$thumbs_update_sql = "UPDATE qna_board SET likes = $thumbs WHERE idx = $getIdx";
$result = mysqli_query($conn, $thumbs_update_sql);


 if($result){
        echo "<script>
            alert('글 추천 완료!');
            location.href ='index.php';
        </script>";
 }else{
   echo "<script>
            alert('글 삭제 완료!');
            location.href ='index.php';
        </script>";
 }


?>
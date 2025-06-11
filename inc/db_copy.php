<?php

    $host = 'localhost';
    $user = 'kytkyt910830';
    $password = 'haha1591!';
    $dbname = 'kytkyt910830';

    // 연결 생성
    $conn = mysqli_connect($host, $user, $password, $dbname);

    // 연결 확인
    if (!$conn) {
        die("연결 실패: " . mysqli_connect_error());
    }
?>
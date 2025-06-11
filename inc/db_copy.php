<?php

    $host = 'localhost';
    $user = '';
    $password = '';
    $dbname = '';

    // 연결 생성
    $conn = mysqli_connect($host, $user, $password, $dbname);

    // 연결 확인
    if (!$conn) {
        die("연결 실패: " . mysqli_connect_error());
    }
?>

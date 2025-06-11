<?php
    require_once('inc/db.php');

    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $message = $_POST['message'];
    $attachment = $_FILES['attachment'];

    // echo "<pre>";
    // print_r($attachment);
    // print_r(count($attachment['name']));
    // echo "</pre>";




    

    // password 입력 및 비입력 처리
    // ! 부정문으로 변경해서 비어있지 않으면 true 반환합니다.
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
  
    // if(isset($_POST['lockpost'])){
    //  $lockpost =1;
    // }else{
    //   $lockpost = 0;
    // }

    $lockpost = isset($_POST['lockpost']) ? 1 : 0 ;
    // sql 문법을 사용해서 데이터를 변수에 할당.
    // INSERT INTO tbl_name (컬럼명, 컬럼명) VALUES(값, 값);   
    $sql = "INSERT INTO qna_board (
        subject,
        username,
        message,
        password,
        lock_post
        ) VALUES(
            '${subject}',
            '${username}',
            '${message}',
            '${password}',
            $lockpost
    )";
    
    $result = mysqli_query($conn, $sql);
    // 첨부된 파일을 데이터베이스에 추가하는 문
    $new_bid = mysqli_insert_id($conn); // 본 글의 idx를 알려준다.


    if(isset($attachment)){
        for($i=0; $i<count($attachment['name']);$i++){
            $tmp_name = $attachment['tmp_name'][$i];
            // $file_name = $attachment['name'];

            $file_name = date("YmdHis").'_'.rand(100, 999);
            // php환경에서 날짜,시간,분,초를 구하는 함수 date(Y-m-d-H-i-s);
            // echo date("YmdHis");
            $file_ext = pathinfo($attachment['name'][$i], PATHINFO_EXTENSION);
            // $file_ext = pathinfo($_FILES['your_file']['name'], PATHINFO_EXTENSION);

            // 상대경로
            $upload_path = './uploads/'.$file_name.'.'.$file_ext;

            //받아온 파일을 별도의 폴더에 저장한다.
            //타겟 경로는 저장될 파일의 이름까지 모두 작성해야한다.
            // move_uploaded_file(임시파일경로, 타겟경로)
            move_uploaded_file($tmp_name, $upload_path);

            // attachment 테이블에 첨부 파일 등록
            $file_sql = "INSERT INTO attachment (
                bid,
                file
                ) VALUES(
                    {$new_bid},
                    '{$upload_path}'
            )";
            $file_result = mysqli_query($conn, $file_sql);
        }
    }



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
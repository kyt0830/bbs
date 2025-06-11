<?php
 // echo $model;

    // 글자의 길이 체크!
    // $str = 'abcde';
    // echo strlen($str);
    // $str1 = '안녕하세요';
    // echo strlen($str1);
    // echo mb_strlen($str1);
    // echo iconv_strlen($str1);
    // $str = 'hello world';
    // $newStr = str_replace('world','universe', $str);
    // echo $newStr;

    //글자 추출하기
    // $str = '안녕하세요';
    // echo mb_substr($str,0,2,'UTF-8');



    // null 병합 연산자 = ?? -> 물음표 두개로 사용합니다.
    // $type = $_GET['type'] ?? '';=
    // if (isset($_GET['type'])) {
    //     $type = $_GET['type'];
    // } else {
    //     $type = '';
    // }

    $type = $_GET['type'] ?? '';
    $keywords = $_GET['keywords'] ?? '';
    $searchParams = '';
    $search ='';
    $url = 'index';
    if(!empty($type) && !empty($keywords)){
        $allowed_types = ['subject','username','message','선택해주세요'];
        if(in_array($type, $allowed_types)){
            // in_array(찾을 값, 배열);
            if($type === '선택해주세요'){
                $search = "WHERE (subject LIKE '%{$keywords}%' or message LIKE '%{$keywords}%')";
            }else{
                $search = "WHERE $type LIKE '%{$keywords}%'";
            }
            $searchParams = "&type={$type}&keywords={$keywords}";
            $url = 'search';
        }else{
            echo "<script>
                alert('정확하지 않습니다. 다시 시도하세요.');
                location.replace('index.php');
            </script>";
        }
    }


// 총 글의 갯수 구하기
 $pageSql = "SELECT COUNT(*) AS cnt FROM qna_board $search";
 $pageResult = mysqli_query($conn, $pageSql);
 $pageData = mysqli_fetch_object($pageResult);
    // print_r($pageData->cnt);

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }

    

    $total_ct = $pageData->cnt; // 게시물 총 갯수
    // print_r($total_ct);
    $countPerPage = 10; // 페이지당 게시물 갯수
    $block_nums = ceil($total_ct / $countPerPage); // 총 페이지 네이션의 갯수
    $block_ct = 5; //페이지 네이션의 갯수
    $start = ($page - 1) * 10; // 시작 번호 값 구하기


    //페이지네이션의 시작번호, 끝번호 만들기.
    $block_grp_ct = ceil($block_nums/$block_ct); //페이지네이션의 총 페이지 갯수
    $current_block = ceil($page / $block_ct); // 현재 블럭 번호
    $block_start = ($current_block - 1) * $block_ct + 1;
    $block_end = $block_start + $block_ct - 1;


    // if($block_end > $block_nums){
    //      $block_end = $block_nums;
    // }
    // $block_end = ($block_end > $block_nums)? $block_nums : $block_end;

    // 둘 중에 작은 걸 반환하는 php함수 min(비교값1, 비교값2);
    $block_end = min($block_end, $block_nums);



    $sql = "SELECT * FROM qna_board $search ORDER BY idx DESC LIMIT {$start},10 ";
    $result = mysqli_query($conn, $sql);
   
    
    $listHTML = '';
    while($data = mysqli_fetch_object($result)){


        $lock = '';
        $replyCount = '';
        $file = '';
     
        $date = mb_substr($data->date,0,10,'UTF-8'); // 원본의 작성 년 월 일
        $currentTime = date("Y-m-d"); // 오늘의 년 월 일
        // 새로운 게시물 확인
        $new = ($date === $currentTime) ? "<i class=\"bi bi-cup-hot\"></i>" : '';

        //첨부파일 확인
        $fileSql = "SELECT COUNT(*) AS cnt FROM attachment WHERE bid = $data->idx";
        $fileResult = mysqli_query($conn, $fileSql);
        $fileData = mysqli_fetch_object($fileResult);
        if($fileData->cnt > 0){
            $file = '<i class="bi bi-paperclip"></i>';
        }


        //제목글변환
        $title = $data->subject;
        if(mb_strlen($title) > 10){
            // mb_substr($data->subject,0,10,'UTF-8')
            $title = str_replace($title,mb_substr($title,0,10,'UTF-8').'...',$title);
        }


        if($data->lock_post == 1){
            $lock = "<i class=\"bi bi-lock-fill\"></i>";
            // $link = 'lock_read';
        }

        // 댓글갯수 확인!
        // reply테이블에서 bid칼럼의 값이 현재 글번호와 일치하는 데이터를 '모두!'조회해서 가져온다.
        // SELECT COUNT(*) AS cnt FROM `article` WHERE memberId = 7
  
        $reply_sql =  "SELECT COUNT(*) AS cnt FROM qna_reply WHERE bid = $data->idx";
        $reply_result = mysqli_query($conn, $reply_sql);
        $reply_data = mysqli_fetch_object($reply_result);
        // print_r($reply_data);
        if(intval($reply_data->cnt)>0){
            $replyCount = "[$reply_data->cnt]";
        }
        // $lock = $data->lockpost === 1 ? '<i class=\"bi bi-lock-fill\"></i>' : '';
        $listHTML .="
        <tr>
            <th scope=\"row\">$data->idx</th>
            <td><a href=\"read.php?idx=$data->idx\">$title $lock $replyCount $file $new</a></td>
            <td>$data->username</td>
            <td>$data->date</td>
            <td>$data->views</td>
            <td>$data->likes</td>
        </tr>
        ";
    }

 
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">제목</th>
            <th scope="col">글쓴이</th>
            <th scope="col">작성일</th>
            <th scope="col">조회</th>
            <th scope="col">추천</th>
        </tr>
    </thead>
    <tbody>

        <?= $listHTML; ?>
        <!-- <tr>
       
        </tr> -->
    </tbody>
</table>

<nav aria-label="Page navigation example" class='d-flex justify-content-center'>
    <ul class="pagination">
        <?php 
            if($page > $block_ct){

                $current_block = ceil($page / $block_ct);
                $prev = ($current_block - 2) * $block_ct + 1;

        ?>
        <li class="page-item"><a class="page-link" href="<?= $url ?>.php?page=1 <?= $searchParams ?>">처음</a></li>
        <li class="page-item"><a class="page-link" href="<?= $url ?>.php?page=<?= $prev ?><?= $searchParams ?>">이전</a></li>
         <?php 
            }
        ?>
        <?php 
            for($i = $block_start; $i <= $block_end; $i++){
        ?>
            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>"><a class="page-link" href="<?= $url ?>.php?page=<?= $i ?><?= $searchParams ?>"><?= $i ?></a></li>
        <?php 
        }
        ?>

        <?php
            if($current_block < $block_grp_ct){
                $next = $block_end + 1;
        ?>
            <li class="page-item"><a class="page-link" href="<?= $url ?>.php?page=<?= $next ?><?= $searchParams ?>">다음</a></li>
            <li class="page-item"><a class="page-link" href="<?= $url ?>.php?page=<?= $block_nums ?><?= $searchParams ?>">마지막</a></li>
        <?php
            }
        ?>
    </ul>
</nav>

<hr>

<form action="search.php" class="search">
    <div class="row">
        <div class="col-md-6">
            <select name="type" class="form-select" aria-label="Default select example">
                <option selected>선택해주세요</option>
                <option value="subject">제목</option>
                <option value="username">글쓴이</option>
                <option value="message">내용</option>
            </select>
        </div>
        <div class="col-md-6 d-flex gap-3">
            <input type="text" name="keywords" class="form-control" placeholder="">
            <button type="submit" class="btn btn-info text-nowrap">검색</button>
        </div>
    </div>
</form>
<hr>
<div class="d-flex justify-content-end">
    <a href="write.php" class="btn btn-primary">글 쓰기</a>
</div>
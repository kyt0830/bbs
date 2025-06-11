<?php



    // db조회 방법 
    // select * from 테이블명;
    // select * from 테이블명 where 칼럼명=값;


    // $_GET['idx'];
    // print_r($_GET['idx']);
    $getIdx = $_GET['idx'];
    $sql = "SELECT * FROM qna_board WHERE idx = $getIdx";
    // print_r($getIdx);
    // print_r($sql);
    $result = mysqli_query($conn, $sql);
    // print_r($result);
    $data = mysqli_fetch_object($result);
    // print_r($data);


    // 1. 변수명 $views에 이 글의 기존 조회수 + 1 할당한다.
    $views = $data->views + 1;    
    // 2. 업데이트 구문을 변수명 $views_update_sql에 할당
    $views_update_sql = "UPDATE qna_board SET views = $views WHERE idx = $getIdx";
    // 값을 수정하는 sql 문법
    // UPDATE qna_board SET views =$views WHERE 조건;
    $result = mysqli_query($conn, $views_update_sql);
    // print_r($views);
        $name = '';
        $date = '';
        $subject = '';
        $content = '';
        $files = '';
        $fileHTML = '';

   //첨부파일 확인
    $file_sql = "SELECT * FROM attachment WHERE bid = $getIdx";
    $file_result = mysqli_query($conn, $file_sql);
        if(isset($file_result)){
            while($file_data = mysqli_fetch_object($file_result)){
                $fileHTML .= getimagesize($file_data->file) ?
                "<div class=\"mb-1\">
                        <img src=\"{$file_data->file}\" alt=\"\">
                </div>'"
                : 
                "<div class=\"mb-1\">
                        <a href=\"\" download>{$file_data->file}</a>
                </div>"
                ;
                
            }
        } 
        

    if($data->lock_post === '1'){
        //비밀글이면.
        echo 
        "<script>
            $(function(){
                $('#postPassVerifyModal').addClass('active');
            });
        </script>";
    }else{
        $name = $data->username;
        $date = $data->date;
        $subject = $data->subject;
        $content = $data->message;
        $files = $fileHTML;
    }


 



            
        //    if(isset($file_result)){
        //     while($file_data = mysqli_fetch_object($file_result)){
        //     echo "<div class=\"mb-3\">";

        //     // print_r($file_data);
        //     if(getimagesize($file_data->file)){
        //      echo "<img src=\"{$file_data->file}\" alt=\" \">";
        //     }else{
        //      echo "<a href=\"{$file_data->file}\" download> {$file_data->file}</a>";
        //     }
        //     // getimagesize(string $filename, array &$image_info = null): array|false

        //     echo "</div>";
        //     }
        //    } 
        
    // print_r($file_data);
    // $attachment = '';
    // if(){
    //     $attachment = 
    // }



?>


<nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 p-3">
    <span id="username"><?= $name; ?></span> - <span id="date"><?= $date; ?></span>
</nav>
<div class="card mb-5">
    <h2 id="subject"> <?= $subject; ?></h2>
    <div class="card-body" id="content">
        <?= $content; ?>
    </div>

    <div class="mb-1" id="files">
            <?= $files; ?>
    </div>
</div>

<hr>
<div class="d-flex justify-content-between">
    <a href="thumbs_up.php?idx=<?=$data->idx?>" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
            <path
                d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z" />
        </svg>
    </a>

    <div class="d-flex gap-1">
        <a href="modify.php?idx=<?=$data->idx?>" class="btn btn-primary">수정</a>
        <a href="#" id="deleteBtn" class="btn btn-danger">삭제</a>
    </div>

</div>
<hr>

<h3>댓글 목록</h3>
<?php 
    // qna_reply table 에서 글 번호와 일치하는 데이터를 조회
    // 결과를 $reply_result에 객체형식으로 할당


    $sql = "SELECT * FROM qna_reply WHERE bid = $getIdx";
    $result = mysqli_query($conn, $sql);

    while($reply_result = mysqli_fetch_object($result)){
?>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4><?= $reply_result->name; ?></h4>
            <p><?= $reply_result->date; ?></p>
        </div>
        <p class="card-text"><?= $reply_result->content; ?></p>
        <div class="d-flex gap-1">

            <button type="button" class="modifyBtn btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#replymodifymodal" data-idx="<?= $reply_result->idx;?>">수정</button>
            <a href="" class="deleteBtn btn btn-danger btn-sm" data-idx="<?= $reply_result->idx;?>"> 삭제 </a>
        </div>
    </div>
</div>

<?php
    }
    ?>


<hr>
<h3>댓글 작성하기</h3>
<form action="reply_ok.php" method="post">
    <input type="hidden" name="bid" value="<?= $getIdx ?>">
    <div class="mb-3">
        <label for="name" class="form-label">이름 : </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="이름을 입력하세요" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">내용 : </label>
        <textarea class="form-control" name="content" id="content" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="repassword" class="form-label">비밀번호 : </label>
        <input type="password" class="form-control" name="repassword" id="repassword" placeholder="비밀번호를 입력하세요"
            required>
    </div>
    <div class="d-flex justify-content-end">
        <button class="btn btn-secondary">댓글 달기</button>
    </div>
</form>
<hr>
<div class="d-flex justify-content-end gap-1">
    <a href="index.php" class="btn btn-secondary">글 목록</a>
    <a href="write.php" class="btn btn-primary">글 쓰기</a>
</div>

<!-- 댓글 수정 모달 -->
<div class="modal fade" id="replymodifymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" id="replymodifyform">
                <input type="hidden" name="idx" id='replymodifyidx' value="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">댓글 수정 모달</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="replymodifycontent" class="form-label">내용 : </label>
                        <textarea class="form-control" name="content" id="replymodifycontent" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="replymodifypassword" class="form-label">비밀번호 : </label>
                        <input type="password" class="form-control" name="repassword" id="replymodifypassword"
                            placeholder="비밀번호를 입력하세요" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="submit" class="btn btn-primary">입력</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 본문 비밀번호 묻는 모달 verify modal -->
<div class="modal" tabindex="-1" id="postPassVerifyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="pwdcheckform">
                <div class="modal-header">
                    <h5 class="modal-title">비밀번호 확인</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="password" class="form-control" id="password" placeholder="비밀번호를 입력하세요." required>
                </div>
                <div class="modal-footer">
                    <button type="button" id="postcancle" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="submit" class="btn btn-primary">확인</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 댓글의 비밀번호 묻는 모달 -->
<div class="modal" tabindex="-1" id="replyPassVerifyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="replypwdcheckform">
                <input type="hidden" name="idx" id="replyidx" value="">
                <div class="modal-header">
                    <h5 class="modal-title">댓글 비밀번호 확인</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="password" class="form-control" id="replypassword" placeholder="비밀번호를 입력하세요." required>
                </div>
                <div class="modal-footer">
                    <button type="button" id="replycancle" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="submit" class="btn btn-primary">확인</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const idx = <?= $getIdx; ?>;

$('#deleteBtn').click(function(evt) {
    evt.preventDefault();
    if (confirm('정말 삭제할까요?')) {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            dataType: 'json',
            data: {
                idx: idx
            },
            success: function(result) { //결과가 있으면 할 일
                if (result.status === 'success') {
                    alert('삭제성공');
                    location.href = 'index.php';
                } else if (result.status === 'fail') {
                    alert('삭제실패');
                }
            },
            error: function() { //의뢰 실패시 할 일
                alert('서버에 오류가 있다!');
            }
        })
    }
})
// 댓글 수정

$('.modifyBtn').click(function(evt) {
    const idx = $(this).attr('data-idx');
    const oldContent = $(this).closest('.card-body').find('.card-text').text();
    $('#replymodifyidx').val(idx);
    $('#replymodifycontent').text(oldContent);
})

$('#replymodifyform').on('submit', function(evt) {
    evt.preventDefault();
    const idx = $('#replymodifyidx').val();
    const content = $('#replymodifycontent').val();
    const passwd = $('#replymodifypassword').val();

    $.ajax({
        url: 'reply_modify.php',
        type: 'POST',
        dataType: 'json',
        data: {
            idx: idx,
            passwd: passwd,
            content: content
        },
        success: function(result) { //결과가 있으면 할 일
            console.log(result);
            if (result.status === 'success') {
                alert('수정 성공');
                location.replace('read.php?idx=<?=$getIdx?>');
            } else if (result.status === 'deletefail') {
                alert('서버 오류 수정 실패');
            } else if (result.status === 'PWerror') {
                alert('비밀번호가 맞지 않습니다.');
            }
        },
        error: function(result) { //의뢰 실패시 할 일
            alert('서버에 오류가 있다!');
        }
    })

})





// 댓글 삭제
$('.deleteBtn').click(function(evt) {
    evt.preventDefault();
    const idx = $(this).attr('data-idx');
    if (confirm('정말 삭제할까요?')) {

        $("#replypwdcheckform > input").val(idx);
        $('#replyPassVerifyModal').addClass('active');



    }
})

$('#replypwdcheckform').on('submit', function(e) {
    e.preventDefault();
    const idx = $('#replyidx').val();
    const passwd = $('#replypassword').val();

    $.ajax({
        url: 'reply_delete.php',
        type: 'POST',
        dataType: 'json',
        data: {
            idx: idx,
            passwd: passwd
        },
        success: function(result) { //결과가 있으면 할 일
            console.log(result);
            if (result.status === 'success') {
                alert('삭제성공');
                location.reload();
            } else if (result.status === 'deletefail') {
                alert('서버 오류 삭제실패');
            } else if (result.status === 'PWerror') {
                alert('비밀번호가 맞지 않습니다.');
            }
        },
        error: function(result) { //의뢰 실패시 할 일
            console.log(result);
            alert('서버에 오류가 있다!');
        }
    })
})


$('#postcancle').add('#postPassVerifyModal .btn-close').click(function() {
    location.href = 'index.php';
})

$('#replyPassVerifyModal .btn-close').click(function() {
    location.href = 'index.php';
})

$('#pwdcheckform').on('submit', function(evt) {
    evt.preventDefault();
    const inputPw = $('#password').val().trim();
    $.ajax({
        url: 'pwdcheck.php', //요청할 파일의 경로
        type: 'POST', // 데이터 전송 방식
        dataType: 'json', // 반환될 데이터의 형식
        data: {
            //전송할 데이터
            idx: idx,
            pwd: inputPw
        },
        success: function(result) { //결과가 있으면 할 일
            console.log(result);
            //
            const fileHTML = `<?= addslashes($fileHTML); ?>`;
            if (result.status === 'success') {
                // 비밀번호가 맞으면 할 일
                $('#username').text(result.username);
                $('#date').text(result.date);
                $('#subject').text(result.subject);
                $('#content').text(result.message);
                $('#files').html(fileHTML);
                $('.modal').removeClass('active');
            } else if (result.status === 'fail') {
                // 비밀번호가 틀리면 할 일
                alert('비밀번호가 일치하지 않습니다.');
                $('#password').focus();
            }
        },
        error: function() { //의뢰 실패시 할 일
            alert('서버에 오류가 있다!');
        }
    })
    console.log(inputPw, idx);
})
</script>
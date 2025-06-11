<?php
//   echo $model;


    // INSERT INTO tbl_name (컬럼명, 컬럼명) VALUES(값, 값);   

    // echo date("YmdHis");
?>

<form action="write_ok.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="username" class="form-label">이름 : </label>
        <input type="text" class="form-control" name="username" id="username" placeholder="이름을 입력하세요" required>
    </div>

    <div class="mb-3">
        <label for="subject" class="form-label">제목 : </label>
        <input type="text" class="form-control" name="subject" id="subject" placeholder="제목을 입력하세요" required>
    </div>

    <div class="mb-3">
        <label for="message" class="form-label">내용 : </label>
        <textarea class="form-control" name="message" id="message" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label for="attachment" class="form-label">첨부파일 : </label>
        <input type="file" name="attachment[]" id="attachment" multiple></input>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">비밀번호 : </label>
        <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호를 입력하세요">
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="lockpost" id="lock">
        <label class="form-check-label" for="checkDefault">
            잠금
        </label>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary">글 쓰기</a>
    </div>
</form>


<script>
$(function() {

    const writeFomr = $('form');
    writeFomr.on('submit', function(evt) {
        evt.preventDefault();
        let userpass = $('#password').val().trim();
        if (userpass > 0) {
            if (!$('#lock').prop('checked')) {
                $('#lock').prop('checked', true);
            }
        }
        if ($('#lock').prop('checked')) {
            // let userpass = $('#password').val().trim();
            if (userpass.length === 0) {
                alert('비번필수!');
                $('#password').focus();
                return;
            }
        }
        writeFomr.off('submit').submit();
    });

})
</script>
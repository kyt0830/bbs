<?php


    $getIdx = $_GET['idx'];
    $sql = "SELECT * FROM qna_board WHERE idx = $getIdx";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_object($result);

    // print_r($data);
?>

<form action="modify_ok.php" method="POST">
    <input type="hidden" name='idx' value="<?= $data->idx ?>">
    <div class="mb-3">
        <label for="username" class="form-label">이름 : </label>
        <input type="text" class="form-control" name="username" id="username" placeholder="이름을 입력하세요"
            value="<?=$data->username?>" required>
    </div>
    <div class="mb-3">
        <label for="subject" class="form-label">제목 : </label>
        <input type="text" class="form-control" name="subject" id="subject" placeholder="제목을 입력하세요"
            value="<?=$data->subject?>" required>
    </div>

    <div class="mb-3">
        <label for="message" class="form-label">내용 : </label>
        <textarea class="form-control" name="message" id="message" rows="3"><?=$data->message?></textarea>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">비밀번호 : </label>
        <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호를 입력하세요">
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary">글 수정</a>
    </div>
</form>
<?php session_start(); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<fieldset style="width: 50%">
    <legend>글보기 글번호<?php echo $boardValue->board_id; ?></legend>
    <form action="../controller/modifyController.php" method="post">
        <table>
            <tr><td>제목</td><td><input type="text" value="<?php echo $boardValue->title; ?>" name="title"></td></tr>
            <tr>
                <td>작성자</td>
                <td>
                    <?php echo $boardValue->user_name; ?>
                    <input type="hidden" value="<?php echo $boardValue->user_name; ?>" name="name">
                </td>
            </tr>
            <tr><td colspan="2"><textarea name='content' cols='80' rows='20'><?php echo $boardValue->contents; ?></textarea></td></tr>
            <tr>
                <td colspan="2">
                    <input type="button" name="list" value="글 목록" onclick="location.href='main.php'">
                    <input type="hidden" name="CheckBoardID" value="<?php echo $boardValue->board_id; ?>">
                    <input type="hidden" name="thisUserName" value="<?php echo $boardValue->usr_name?>">
                    <input type="submit" name="modify" value="글 수정" onclick="location.href='../controller/modifyController.php'">
                </td>
            </tr>
        </table>
    </form>
</fieldset>
</body>
</html>
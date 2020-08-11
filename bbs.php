<?php

$errors = array();

// POSTなら保存処理続行
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //名前が正しく入力されているかチェック
    $name = null;
    if(!isset($_POST['name']) || !strlen($_POST['name'])) {
        $errors['name'] = '名前を入力してください。';
    } else if(strlen($_POST['name']) > 40) {
        $errors['name'] = '名前は４０文字以内で入力してください。';
    } else {
        $name = $_POST['name'];
    }

    // ひとことが正しく入力されているかチェック
    $comment = null;
    if(!isset($_POST['comment']) || !strlen($_POST['comment'])) {
        $errors['comment'] = 'ひとことを入力してください。';
    } else if(strlen($_POST['comment']) > 200) {
        $errors['comment'] = 'ひとことは２００文字以内で入力してください。';
    } else {
        $comment = $_POST['comment'];
    }

    // 日時取得を日本時間に合わせる
    date_default_timezone_set("Asia/Tokyo");
    $created_at = date('Y/m/d H:i:s');

    // エラーがなければ保存
    if(count($errors) === 0) {
        // classファイル呼び出し
        require_once "db.php";

        // インスタンス
        $db_contact = new S_contact();
        // insメソッドの実行
        $db_contact->ins($name, $comment, $created_at);
        header('Location: bbs.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>

    <form action="bbs.php" method="post">
        <?php if(count($errors)): ?>
            <ul class="error_list">
                <?php foreach($errors as $error): ?>
                    <li>
                        <?php echo $error ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    名前： <input type="text" name="name"><br>
    ひとこと： <input type="text" name="comment" size="60"><br>
    <input type="submit" name="submit" value="送信">
    </form>

<?php
// classファイル呼び出し
require_once "db.php";

//インスタンス
$db_contact = new S_contact();
$data = $db_contact->sel();

foreach($data as $row) : ?><br>
    <li>
        <?php

        // functionファイル呼び出し
        require_once "function.php";
        echo h($row['name']), PHP_EOL;
        echo h($row['comment']), PHP_EOL;
        echo h($row['created_at']);
        ?><br>
    </li>
<?php endforeach; ?>

</body>
</html>
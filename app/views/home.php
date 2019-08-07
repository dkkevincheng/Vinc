<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
</head>

<body>
    <div class="article">
        <h1><?php echo $article['title'] ?></h1>
        <div class="content">
            <?php echo $article['content'] ?>
        </div>
    </div>
    <ul class="fuckme">
        <li>欢迎使用!</li>
        <li>
            <?php echo $content ?>
        </li>
    </ul>
</body>

</html>
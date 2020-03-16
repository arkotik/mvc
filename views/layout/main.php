<?php

use core\View;

/**
 * @var $this View
 * @var $content string
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>App</title>
</head>
<body>
    <?= $this->render('header') ?>
    <?= $this->render('content', compact('content')) ?>
    <?= $this->render('footer') ?>
</body>
</html

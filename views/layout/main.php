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
    <link rel="stylesheet" href="/web/assets/styles.css">
</head>
<body>
    <div class="layout-wrapper">
        <?= $this->render('header') ?>
        <?= $this->render('content', compact('content')) ?>
        <?= $this->render('footer') ?>
    </div>
</body>
</html

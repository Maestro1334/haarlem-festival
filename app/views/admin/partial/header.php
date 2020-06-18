<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bootstrap.min.css">
    <script defer src="<?php echo URLROOT; ?>/js/all.js"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/style.css">
    <!-- Custom styling -->
    <?php
    if (isset($styling)) {
        foreach ($styling as $key => $file) {
            echo '<link rel="stylesheet" href="' . URLROOT . '/public/css/' . $file . '"></script>';
        }
    }
    ?>
    <title><?php echo SITENAME; ?></title>
</head>

<body>
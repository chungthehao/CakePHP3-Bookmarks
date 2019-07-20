<?php $this->assign('title', $title); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body>
    <h2>Chào mày,</h2>
    <?= $this->fetch('content') ?>
</body>
</html>
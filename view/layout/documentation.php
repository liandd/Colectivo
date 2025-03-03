<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Documentation' ?></title>
    <link rel="stylesheet" href="/css/documentation.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</head>
<body>
    <header>
        <h1><?= $pageTitle ?? 'Documentation' ?></h1>
    </header>

    <?php if (isset($tableOfContents)): ?>
    <div class="content-table">
        <h3>Table of Contents</h3>
        <?= $tableOfContents ?>
    </div>
    <?php endif; ?>

    <div class="content">
        <?php require_once $contentView; ?>
    </div>

    <footer>
        &copy; <?= date('Y') ?> - All rights reserved
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- The page title is set by each page using `$pageTitle` before the `include` statement; if not set, the default value is used. -->
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Finova Technologies'; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <div class="site-header">
            <a href="index.php" class="logo">
                FINOVA
            </a>

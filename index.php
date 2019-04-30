<html lang="en">
<header>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</header>
<body>
<?php include_once 'style/header.php'; ?>
<?php include_once 'bin/SessionController.php'; verifySession(); ?>
<p>
<?php
include_once 'bin/CSVProcessor.php';
if (isset($_SESSION['filename'])) {
    echo '<div id="data-table" style="padding-left: 1%; padding-right: 1%;">';
    $csvData = processFile($_SESSION['filename']);
    if (isset($_GET['column'])) {
        echoSortedTable($csvData, $_GET['column']);
    } else {
        echoRawTable($csvData);
    }
    echo '</div>';
} else {
    echo 'Please upload a file to begin!';
}

?>

<?php include_once 'style/footer.php'; ?>
</body>
</html>
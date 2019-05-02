<!DOCTYPE html>
<html lang="en">
<header>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
</header>
<body>
<?php include_once 'style/header.php'; ?>
<?php include_once 'bin/SessionController.php'; verifySession(); ?>
<p>
<?php
ini_set('memory_limit', '-1');
include_once 'bin/CSVProcessor.php';
if (isset($_SESSION['filename'])) {
    echo '<div id="data-table" style="padding-left: 1%; padding-right: 1%;">';
    $csvData = processFile($_SESSION['filename']);
    if (isset($_GET['column'])) {
        if (isset($_GET['asc']) && $_GET['asc'] == 'false') {
            echoSortedTable($csvData, $_GET['column'], true);
        } else {
            echoSortedTable($csvData, $_GET['column'], false);
        }
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
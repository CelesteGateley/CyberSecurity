<html lang="en">
<header>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
</header>
<body>
<?php include_once 'style/header.php'; if (!isset($_GET['column'], $_GET['value'], $_SESSION['filename'])) { echo '<script type="text/javascript">location.href = "index.php";</script>'; } ?>
<p>
<form action="output.php" method="get">
    <input type="hidden" name="column" value="<?php echo $_GET['column'] ?>">
    <input type="hidden" name="value" value="<?php echo $_GET['value'] ?>">
    <input type="submit" value="Download CSV Data">
</form>


<?php
    include_once 'bin/CSVProcessor.php';
    $csvData = processFile($_SESSION['filename']);
    $arr = echoValueTable($csvData, $_GET['column'], $_GET['value']);
?>
</p>
    <?php include_once 'style/footer.php'; ?>
</body>
</html>
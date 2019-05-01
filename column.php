<html lang="en">
<header>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
</header>
<body>
<?php include_once 'style/header.php'; ?>
<p>

<?php if (!isset($_GET['column'], $_SESSION['filename'])) { echo '<script type="text/javascript">location.href = "index.php";</script>'; }
include_once 'bin/CSVProcessor.php';
$csvData = processFile($_SESSION['filename']);
echoFrequencyTable($csvData, $_GET['column'])?>

<?php include_once 'style/footer.php'; ?>
</body>
</html>
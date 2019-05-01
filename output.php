<html lang="en">
<header>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
</header>
<body>
<p>

    <?php
        include_once 'bin/CSVProcessor.php';
        $csvData = processFile($_SESSION['filename']);
        $arr = echoValueTable($csvData, $_GET['column'], $_GET['value'], false);
        $fileName = storeToFile($arr);
        //echo $fileName;
        echo '<script type="text/javascript">location.href = "./'. $fileName .'";</script>';
        echo '<script type="text/javascript">location.href = "index.php";</script>';

    ?>

</body>
</html>
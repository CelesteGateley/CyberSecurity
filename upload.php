<?php
include 'bin/SessionController.php';
verifySession();
$target_dir = 'uploads/';
$fileName = uniqid(mt_rand(), false) . '.csv';
$target_file = $target_dir . $fileName;
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
while (file_exists($target_file)) {
    $fileName = uniqid(mt_rand(), false) . '.csv';
    $target_file = $target_dir . $fileName;
}

// Allow certain file formats
if($fileType !== 'csv' && $fileType !== 'txt') {
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk === 0) {
    echo '<script type="text/javascript">alert("Sorry, only CSV or TXT files are allowed.");</script>';
    echo '<script type="text/javascript">location.href = "index.php";</script>';
    echo '';
// if everything is ok, try to uploads file
} else if (move_uploaded_file($_FILES['csvFile']['tmp_name'], $target_file)) {
    $_SESSION['filename'] = $fileName;
    echo '<script type="text/javascript">alert("The file ' . basename($_FILES['csvFile']['name']). ' has been uploaded.");</script>';
    echo '<script type="text/javascript">location.href = "index.php";</script>';
} else {
    echo '<script type="text/javascript">alert("Sorry, there was an error uploading your file.");</script>';
    echo '<script type="text/javascript">location.href = "index.php";</script>';
}

<?php
include_once 'bin/SessionController.php';
verifySession();
if (isset($_GET['filename'])) {
    $_SESSION['filename'] = $_GET['filename'];
}
echo '<script type="text/javascript">location.href = "index.php";</script>';
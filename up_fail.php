<?php
include_once 'bin/SessionController.php';
verifySession();
if (isset($_GET['filename'])) {
    $_SESSION['filename'] = $_GET['filename'];
} else if ($_SESSION['filname']) {
    unset($_SESSION['filename']);
}
echo '<script type="text/javascript">location.href = "index.php";</script>';
<div id="header" style="width: 100%; border-bottom: 1px solid black;">

    <div id="uploadFile" style="width: 90%; display: inline-block;">
    <form action="upload.php" method="post" enctype="multipart/form-data" >
        <input type="file" name="csvFile" id="csvFile" onchange="this.form.submit()" accept=".csv,.txt">
    </form>
    </div>
    <div id="viewRawData" style="width: 7%; display: inline-block;">
    <?php
        include 'bin/SessionController.php';
        verifySession();
        if (isset($_SESSION['filename'])) {
            echo '<form action="uploads/' . $_SESSION['filename'] . '"><input type="submit" value="Download Raw Data"></form>';
        }
    ?>
    </div>
</div>
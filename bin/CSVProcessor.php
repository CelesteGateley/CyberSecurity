<?php /** @noinspection TypeUnsafeComparisonInspection */
include_once 'SessionController.php'; verifySession();

function storeToFile(array $arr, string $directory='outputs/', string $fileName='') : string {
    $target_dir = $directory;
    if ($fileName === '') { $fileName = uniqid(mt_rand(), false) . '.csv'; }
    $target_file = $target_dir . $fileName;
    $file = fopen($target_file, 'wb');
    foreach ($arr as $row) { fputcsv($file, $row); }
    fclose($file);
    return $target_file;
}

function processFile(string $fileName) {
    $data = array();
    $rowCount = 0;
    $handle = fopen('uploads/' . $fileName, 'rb');
    if ($handle) {
        while (($row = fgetcsv($handle, 1024)) !== FALSE) {
            //index of $data hold whole array
            $data[$rowCount] = $row;
            $rowCount++;
        }
        fclose($handle);
    }
    return $data;
}

function getFrequency(array $arr) {
    $freqArray = array();
    foreach ($arr as $key => $value) {
        if (isset($freqArray[$value])) {
            $freqArray[$value]++;
        } else {
            $freqArray[$value] = 1;
        }
    }
    return $freqArray;
}

function echoRawTable(array $csvData) {
    set_time_limit(0);
    $tableSize = count($csvData[0]);
    echo '<table class="table table-bordered">';
    echoTableHeader($tableSize, -1, true);
    foreach ($csvData as $key => $value) {
        echo '<tr>';
        foreach ($value as $item) { echo '<td>' . $item . '</td>'; }
        echo '</tr>';
    }
    echo '</table>';
}

function echoTableHeader(int $count, int $col, bool $asc) {
    echo '<tr>';
    for ($x = 0; $x < $count; $x++) {
        echo '<th style="text-align: center;">';
        //$val = ($col !== -1 && !$asc) ? 'false' : 'true';
        if ($x === $col) {
            echo '<form action="column.php" method="get"><input type="submit" name="column" value="' . $x . '"></form>';
        } else {
            echo '<form action="column.php" method="get"><input type="submit" name="column" value="' . $x . '"></form>';
        }
        echo '</th>';
    }
    echo '</tr>';
}

function echoFrequencyTable(array $csvData, int $column, bool $out=true) {
    $totalRows = count($csvData);
    $tableSize = count($csvData[0]);
    if ($column > $tableSize - 1) { echo '<script type="text/javascript">location.href = "index.php";</script>'; return $csvData; } // Verify Column is in array
    $tempArray = array();
    foreach ($csvData as $key => $value) { $tempArray[$key] = $value[$column]; } // Generate Array of column values
    $freqArr = getFrequency($tempArray);
    asort($freqArr);
    if ($out) {
        echo '<table style="width: 10%; padding: 5px; text-align: center;"><tr><th>Value</th><th>Frequency</th></tr>';
        foreach ($freqArr as $value => $frequency) {
            $color = (($frequency+($frequency/10)) > ($totalRows / count($freqArr))) ? '#94ff82' : '#ff8282';
            echo '<tr style="background: '.$color.';">';
            echo '<td><form action="row.php" method="get"><input type="hidden" name="column" value="'.$column.'"><input type="submit" name="value" value="'.$value.'"></form></td>';
            echo '<td>' . $frequency . '</td>';
            echo '</tr>';
        }
        echo '<tr><td><b>Total</b></td><td>'.$totalRows.'</td></tr>';
        echo '</table>';
    }
    return $freqArr;
}

function echoValueTable(array $csvData, int $column, $value, bool $out=true) {
    set_time_limit(0);
    $tableSize = count($csvData[0]);
    echo '<table class="table table-bordered">';
    $newArr = array();
    foreach ($csvData as $key => $val) { if ($val[$column] == $value) { $newArr[] = $val; } }
    if ($out) {
        echoTableHeader($tableSize, -1, true);
        foreach ($newArr as $key => $val) {
            echo '<tr>';
            foreach ($val as $item) { echo '<td>' . $item . '</td>'; }
            echo '</tr>';
        }
        echo '</table>';
    }
    return $newArr;
}
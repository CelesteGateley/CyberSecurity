<?php
include_once 'SessionController.php'; verifySession();


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

function echoTableHeader(int $count, int $col, bool $asc) {
    echo '<tr>';
    for ($x = 0; $x < $count; $x++) {
        echo '<th style="text-align: center;">';
        $val = ($col !== -1 && !$asc) ? 'false' : 'true';
        if ($x === $col) {
            echo '<form action="index.php" method="get"><input type="hidden" name="asc" value="' . $val . '"><input type="submit" name="column" value="' . $x . '"></form>';
        } else {
            echo '<form action="index.php" method="get"><input type="hidden" name="asc" value="true"><input type="submit" name="column" value="' . $x . '"></form>';
        }
        echo '</th>';
    }
    echo '</tr>';
}

function echoRawTable(array $csvData) {
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

function getHighestFrequency(array $arr) {
    $highest = 0;
    foreach ($arr as $key => $freq) {
        if ($freq > $highest) {
            $highest = $freq;
        }
    }
    return $highest;

}

/*
 * Above: ff9191
 * Average: 91ffa4
 * Below: ff9191
 */

function echoSortedTable(array $csvData, int $column, bool $asc) {
    $tableSize = count($csvData[0]);
    if ($column > $tableSize - 1) { echoRawTable($csvData); return; } // Verify Column is in array
    $tempArray = array();
    foreach ($csvData as $key => $value) { $tempArray[$key] = $value[$column]; } // Generate Array of column values
    $sort = $asc ? SORT_ASC : SORT_DESC;
    array_multisort($tempArray, $sort, $csvData);
    $freqArray = getFrequency($tempArray);
    $avg = getHighestFrequency($freqArray);
    $lowAvg = $avg - ($avg/10);
    $highAvg = $avg + ($avg/10);
    echo '<table>';
    echoTableHeader($tableSize, $column, $asc);
    foreach ($csvData as $key => $value) {
        $freq = $freqArray[$value[$column]];
        if ($freq < $lowAvg) { echo '<tr style="background: #ff9191">'; }
        else if ($freq > $lowAvg && $freq < $highAvg) { echo '<tr style="background: #91ffa4">'; }
        else { echo '<tr style="background: #9191ff">'; }
        foreach ($value as $item) {
            echo '<td>' . $item . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}
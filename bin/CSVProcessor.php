<?php
include_once 'SessionController.php'; verifySession();

function storeToFile(array $arr) : string {
    $target_dir = 'outputs/';
    $fileName = uniqid(mt_rand(), false) . '.csv';
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

function isAbnormal(array $freqArray, $value) {
    sort($freqArray, SORT_DESC);
    $prevVal = -1;
    foreach ($freqArray as $key => $frequency) {
        if ($prevVal === -1) { $prevVal = $frequency; }
        if ($key === $value) {
            return ($prevVal/4) < $frequency;
        }
    }
    return false;
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
    $tableSize = count($csvData[0]);
    if ($column > $tableSize - 1) { echo '<script type="text/javascript">location.href = "index.php";</script>'; return $csvData; } // Verify Column is in array
    $tempArray = array();
    foreach ($csvData as $key => $value) { $tempArray[$key] = $value[$column]; } // Generate Array of column values
    $freqArr = getFrequency($tempArray);
    asort($freqArr);
    if ($out) {
        echo '<table style="width: 10%; padding: 5px; text-align: center;"><tr><th>Value</th><th>Frequency</th></tr>';
        foreach ($freqArr as $value => $frequency) {
            echo '<tr>';
            echo '<td><form action="row.php" method="get"><input type="hidden" name="column" value="'.$column.'"><input type="submit" name="value" value="'.$value.'"></form></td>';
            echo '<td>' . $frequency . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    return $freqArr;
}

function echoSortedTable(array $csvData, int $column, bool $asc, bool $out=true) {
    $tableSize = count($csvData[0]);
    if ($column > $tableSize - 1) { echoRawTable($csvData); return $csvData; } // Verify Column is in array
    $tempArray = array();
    foreach ($csvData as $key => $value) { $tempArray[$key] = $value[$column]; } // Generate Array of column values
    $sort = $asc ? SORT_ASC : SORT_DESC;
    array_multisort($tempArray, $sort, $csvData);
    $freqArray = getFrequency($tempArray);
    $avg = getHighestFrequency($freqArray);
    $lowAvg = $avg - ($avg/10);
    $highAvg = $avg + ($avg/10);
    if ($out) {
        echo '<table>';
        echoTableHeader($tableSize, $column, $asc);
        foreach ($csvData as $key => $value) {
            $freq = $freqArray[$value[$column]];
            if (isAbnormal($freqArray, $value[$column])) { echo '<tr style="background: #ff9191">'; }
            else { echo '<tr style="background: #91ffa4">'; }
            foreach ($value as $item) {
                echo '<td>' . $item . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    return $csvData;
}

function echoValueTable(array $csvData, int $column, $value, bool $out=true) {
    $tableSize = count($csvData[0]);
    echo '<table class="table table-bordered">';
    $newArr = array();
    foreach ($csvData as $key => $val) { if ($val[$column] == $value) { $newArr[] = $val; } }
    if ($out) {
        echoTableHeader($tableSize, -1, true);
        foreach ($newArr as $key => $val) {
            echo '<tr>';
            foreach ($val as $item) {
                echo '<td>' . $item . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    return $newArr;
}
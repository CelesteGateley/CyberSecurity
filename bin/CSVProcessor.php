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

function echoTableHeader(int $count) {
    echo '<tr>';
    for ($x = 0; $x < $count; $x++) {
        echo '<th>';
        echo '<form action="index.php" method="get"><input type="submit" name="column" value="' . $x .'"></form>';
        echo '</th>';
    }
    echo '</tr>';
}

function echoRawTable(array $csvData) {
    $tableSize = count($csvData[0]);
    echo '<table>';
    echoTableHeader($tableSize);
    foreach ($csvData as $key => $value) {
        echo '<tr>';
        foreach ($value as $item) { echo '<td>' . $item . '</td>'; }
        echo '</tr>';
    }
    echo '</table>';
}

function echoSortedTable(array $csvData, int $column) {
    $tableSize = count($csvData[0]);
    if ($column > $tableSize - 1) {
        echoRawTable($csvData);
        return;
    }
    $tempArray = array();
    foreach ($csvData as $key => $value) {
        $tempArray[$key] = $value[$column];
    }
    array_multisort($tempArray, SORT_ASC, $csvData);
    echoRawTable($csvData);
}
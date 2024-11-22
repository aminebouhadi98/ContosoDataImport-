<?php

// Controllo se la cartella esiste, altrimenti la creo
$directory = 'C:/xampp82/htdocs/ContosoDataImport/storage/app/import-files';
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Template per creare i file Excel in formato XML
$excelTemplate = <<<EOT
<?xml version="1.0"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
          xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:x="urn:schemas-microsoft-com:office:excel"
          xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
    <Worksheet ss:Name="Sheet1">
        <Table>
            <!-- ROWS_PLACEHOLDER -->
        </Table>
    </Worksheet>
</Workbook>
EOT;

// Genero 1000 file Excel
for ($i = 1; $i <= 1000; $i++) {
    $rows = '';

    // Aggiungo l'intestazione delle colonne
    $rows .= '<Row>';
    $rows .= '<Cell><Data ss:Type="String">Branch ID</Data></Cell>';
    $rows .= '<Cell><Data ss:Type="String">Date</Data></Cell>';
    $rows .= '<Cell><Data ss:Type="String">Balance</Data></Cell>';
    $rows .= '</Row>';

    // Creo un Branch ID fisso per il file
    $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
    $numbers = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $branchId = $letters . $numbers;

    // Aggiungo righe di dati casuali
    $numRows = rand(10, 100); // Numero casuale di transazioni
    for ($j = 0; $j < $numRows; $j++) {
        // Creo una data casuale
        $date = sprintf('%02d/%02d/%d', rand(1, 28), rand(1, 12), rand(2020, 2023));

        // Genero un saldo casuale, positivo o negativo
        $balance = number_format(rand(-10000, 10000) / 100, 2, '.', '');

        // Aggiungo una riga con i dati generati
        $rows .= '<Row>';
        $rows .= '<Cell><Data ss:Type="String">' . $branchId . '</Data></Cell>';
        $rows .= '<Cell><Data ss:Type="String">' . $date . '</Data></Cell>';
        $rows .= '<Cell><Data ss:Type="Number">' . $balance . '</Data></Cell>';
        $rows .= '</Row>';
    }

    // Inserisco i dati nel template
    $excelData = str_replace('<!-- ROWS_PLACEHOLDER -->', $rows, $excelTemplate);

    // Salvo il file nella cartella, includendo il Branch ID nel nome
    $filePath = $directory . "/financial_data_$branchId.xlsx";
    file_put_contents($filePath, $excelData);
}

// Messaggio di completamento
echo "Tutti i file Excel sono stati generati nella cartella: $directory\n";

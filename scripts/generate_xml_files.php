<?php

// Controllo se la cartella esiste, se non c'è la creo
$directory = 'C:/xampp82/htdocs/ContosoDataImport/storage/app/import-files';
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Genero 1000 file XML
for ($i = 1; $i <= 1000; $i++) {
    // Creo un Branch ID unico per il file
    $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
    $numbers = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $branchId = $letters . $numbers;

    // Inizio della struttura XML
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xmlContent .= '<FinancialData>' . PHP_EOL;
    $xmlContent .= '    <BranchID>' . $branchId . '</BranchID>' . PHP_EOL;
    $xmlContent .= '    <Transactions>' . PHP_EOL;

    // Genero righe di transazioni casuali
    $numRows = rand(10, 100); // Numero casuale di transazioni per file
    for ($j = 0; $j < $numRows; $j++) {
        // Genero una data casuale
        $date = sprintf('%02d/%02d/%d', rand(1, 28), rand(1, 12), rand(2020, 2023));

        // Genero un saldo casuale, positivo o negativo
        $balance = number_format(rand(-10000, 10000) / 100, 2, '.', '');

        // Aggiungo una transazione al contenuto XML
        $xmlContent .= '        <Transaction>' . PHP_EOL;
        $xmlContent .= '            <Date>' . $date . '</Date>' . PHP_EOL;
        $xmlContent .= '            <Balance>' . $balance . '</Balance>' . PHP_EOL;
        $xmlContent .= '        </Transaction>' . PHP_EOL;
    }

    // Fine della struttura XML
    $xmlContent .= '    </Transactions>' . PHP_EOL;
    $xmlContent .= '</FinancialData>' . PHP_EOL;

    // Percorso del file XML con il Branch ID nel nome
    $filePath = $directory . "/financial_data_{$branchId}.xml";

    // Scrivo il contenuto nel file
    file_put_contents($filePath, $xmlContent);
}

// Messaggio per confermare che i file sono stati creati
echo "Tutti i file XML sono stati generati nella cartella: $directory\n";

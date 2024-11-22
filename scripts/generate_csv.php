<?php

// Controllo se la cartella esiste, se non c'è la creo
$directory = 'C:/xampp82/htdocs/ContosoDataImport/storage/app/import-files';
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Genero 1000 file CSV
for ($i = 1; $i <= 1000; $i++) {
    // Creo un Branch ID unico per ogni file
    $letters = strtoupper(string: substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
    $numbers = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $branchId = $letters . $numbers;

    // Definisco il percorso del file, includendo il Branch ID nel nome
    $filePath = $directory . "/financial_data_{$branchId}.csv";

    // Apro il file in modalità scrittura
    $file = fopen($filePath, 'w');

    // Scrivo l'intestazione nel file
    fputcsv($file, ['Branch ID', 'Date', 'Balance']);

    // Genero righe casuali per le transazioni
    $numRows = rand(10, 100); // Ogni file avrà un numero variabile di righe
    for ($j = 0; $j < $numRows; $j++) {
        // Genero una data casuale
        $date = sprintf('%02d/%02d/%d', rand(1, 28), rand(1, 12), rand(2020, 2023));

        // Genero un saldo casuale, positivo o negativo
        $balance = number_format(rand(-10000, 10000) / 100, 2, '.', '');

        // Aggiungo una riga con i dati generati
        fputcsv($file, [$branchId, $date, $balance]);
    }

    // Chiudo il file dopo aver scritto tutte le righe
    fclose($file);
}

// Stampo un messaggio per confermare che i file sono stati creati
echo "Tutti i file CSV sono stati generati nella cartella: $directory\n";

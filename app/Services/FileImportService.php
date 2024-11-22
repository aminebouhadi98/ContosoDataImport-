<?php

namespace App\Services;

use App\Services\Importers\FileImporterInterface;
use App\Services\Importers\CsvImporter;
use App\Services\Importers\ExcelImporter;
use App\Services\Importers\XmlImporter;
use Illuminate\Support\Facades\Log;

class FileImportService
{
    // Associo i tipi di file agli importer corrispondenti
    private $importers = [
        'csv' => CsvImporter::class,   // Per i file CSV
        'xlsx' => ExcelImporter::class, // Per i file Excel
        'xml' => XmlImporter::class, // Per i file XML (commentato)
    ];

    public function run()
    {
        // Prendo la cartella con i file da importare
        $importPath = storage_path('app/import-files');

        // Controllo se la cartella esiste, altrimenti scrivo un errore nei log
        if (!is_dir($importPath)) {
            Log::error("La cartella di importazione non esiste: $importPath");
            return;
        }

        // Leggo tutti i file nella cartella
        foreach (scandir($importPath) as $file) {
            // Salto i file speciali '.' e '..'
            if ($file === '.' || $file === '..') {
                continue;
            }

            // Percorso completo del file
            $filePath = "$importPath/$file";

            // Gestisco l'importazione del file
            $this->handleFile($filePath, $file);
        }
    }

    private function handleFile($filePath, $fileName)
    {
        // Prendo l'estensione del file
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Controllo se l'estensione è supportata
        if (!isset($this->importers[$extension])) {
            Log::warning("Formato file non supportato: $fileName");
            return;
        }

        // Creo l'importer corrispondente
        $importerClass = $this->importers[$extension];
        $importer = new $importerClass();

        // Faccio partire l'importazione
        $importer->import($filePath);

        // Cartella per spostare i file già processati
        $processedDir = storage_path('app/processed-files');

        // Se la cartella non esiste, la creo
        if (!is_dir($processedDir)) {
            mkdir($processedDir, 0755, true);
        }

        // Sposto il file nella cartella dei file processati
        rename($filePath, "$processedDir/$fileName");
    }
}

<?php

namespace App\Services\Importers;

use App\Models\FinancialRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CsvImporter implements FileImporterInterface
{
    public function import(string $filePath): void
    {
        $chunkSize = 100; // Inserisco i dati in blocchi per evitare problemi di memoria
        $rows = [];

        // Apro il file CSV
        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Ignoro la prima riga perché è l'intestazione

            // Leggo il file riga per riga
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    // Preparo i dati per il database
                    $rows[] = [
                        'branch_id' => $row[0], // ID della filiale
                        'date' => Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'), // Converto la data
                        'balance' => floatval(str_replace(',', '.', $row[2])), // Converto il saldo
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } catch (\Exception $e) {
                    // Se c'è un errore con una riga, lo registro
                    Log::error("Errore con il record nel file $filePath: " . implode(',', $row) . " - " . $e->getMessage());
                }

                // Quando ho abbastanza righe, le salvo nel database
                if (count($rows) === $chunkSize) {
                    FinancialRecord::insert($rows);
                    $rows = []; // Ripulisco per il prossimo blocco
                }
            }

            // Inserisco eventuali righe rimaste
            if (!empty($rows)) {
                FinancialRecord::insert($rows);
            }

            // Chiudo il file alla fine
            fclose($handle);
        }
    }
}

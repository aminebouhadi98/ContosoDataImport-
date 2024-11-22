<?php

namespace App\Services\Importers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\FinancialRecord;
use App\Services\Importers\FileImporterInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExcelImporter implements FileImporterInterface
{
    public function import(string $filePath): void
    {
        try {
            // Apro il file Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            $chunkSize = 100; // Inserisco i dati in blocchi
            $rows = [];

            // Scorro tutte le righe del foglio Excel
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index === 1) continue; // Salto l'intestazione

                // Estraggo i valori di ogni cella nella riga
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                try {
                    // Preparo i dati da salvare nel database
                    $rows[] = [
                        'branch_id' => $data[0],
                        'date' => Carbon::createFromFormat('d/m/Y', $data[1])->format('Y-m-d'),
                        'balance' => floatval(str_replace(',', '.', subject: $data[2])),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } catch (\Exception $e) {
                    // Loggo eventuali errori per una riga specifica
                    Log::error("Errore nella riga $index del file $filePath: " . $e->getMessage());
                }

                // Quando raggiungo il blocco definito, salvo i dati
                if (count($rows) === $chunkSize) {
                    FinancialRecord::insert($rows);
                    Log::info("$chunkSize record inseriti dal file $filePath.");
                    $rows = []; // Ripulisco per il prossimo blocco
                }
            }

            // Inserisco eventuali righe rimaste
            if (!empty($rows)) {
                FinancialRecord::insert($rows);
                Log::info(count($rows) . " record rimanenti inseriti dal file $filePath.");
            }

        } catch (\Exception $e) {
            // Loggo un errore generale se il file non può essere processato
            Log::error("Errore con il file $filePath: " . $e->getMessage());
        }
    }
}

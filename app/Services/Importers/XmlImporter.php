<?php

namespace App\Services\Importers;

use App\Models\FinancialRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class XmlImporter implements FileImporterInterface
{
    public function import(string $filePath): void
    {
        $chunkSize = 100; // Inserisco i dati nel database a blocchi, così è più efficiente
        $rows = [];

        try {
            $xml = simplexml_load_file($filePath); // Carico il file XML

            // Scorro tutte le transazioni trovate nel file
            foreach ($xml->Transactions->Transaction as $transaction) {
                try {
                    $rows[] = [
                        'branch_id' => (string) $xml->BranchID, 
                        'date' => Carbon::createFromFormat('d/m/Y', (string) $transaction->Date)->format('Y-m-d'),
                        'balance' => floatval((string) $transaction->Balance), // Converto il saldo
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } catch (\Exception $e) {
                    // Se qualcosa va storto con una transazione, lo registro nei log
                    Log::error("Errore con il record nel file $filePath: " . json_encode($transaction) . " - " . $e->getMessage());
                }

                // Quando raggiungo il numero massimo di righe nel blocco, salvo i dati
                if (count($rows) === $chunkSize) {
                    FinancialRecord::insert($rows);
                    $rows = []; // Ripulisco per preparare il prossimo blocco
                }
            }

            // Se ci sono ancora righe rimaste, le inserisco
            if (!empty($rows)) {
                FinancialRecord::insert($rows);
            }

        } catch (\Exception $e) {
            // Qualcosa è andato storto con l'apertura o la lettura del file XML
            Log::error("Errore nel file $filePath: " . $e->getMessage());
        }
    }
}

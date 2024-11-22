<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FileImportService;
use App\Jobs\ImportFinancialDataJob;

class ImportFinancialData extends Command
{
    // Definizione del comando da eseguire tramite CLI
    protected $signature = 'import:financial-data';

    // Descrizione del comando
    protected $description = 'Importa i dati finanziari dai file CSV/Excel';

    public function handle()
    {
        // Messaggio iniziale per indicare l'inizio del processo
        $this->info('Inizio del processo di importazione...');

        // Avvio del job per l'importazione in background
        ImportFinancialDataJob::dispatch();

        // Messaggio per confermare che il job è stato avviato
        $this->info('Importazione avviata in background.');
    }
}

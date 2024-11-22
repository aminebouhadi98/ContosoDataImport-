<?php

namespace App\Jobs;

use App\Services\FileImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportFinancialDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Questo metodo viene eseguito quando il job parte
    public function handle()
    {
        // Creo un'istanza del servizio di importazione
        $importService = new FileImportService();

        // Avvio il processo di importazione
        $importService->run();
    }
}

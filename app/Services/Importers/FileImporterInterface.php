<?php

namespace App\Services\Importers;

/**
 * Interfaccia FileImporterInterface
 * 
 * Questa interfaccia definisce il contratto che ogni classe importer deve seguire.
 * Garantisce che tutte le classi che la implementano (come CsvImporter ed ExcelImporter)
 * abbiano un metodo `import` con una struttura uniforme.
 */
interface FileImporterInterface
{
    /**
     * Metodo per importare i dati da un file.
     * 
     * @param string $filePath Il percorso completo del file da importare.
     * 
     * @return void
     * 
     * Ogni implementazione di questo metodo deve definire come gestire il file
     * (ad esempio leggere i dati, convertirli e salvarli nel database).
     */
    public function import(string $filePath): void;
}

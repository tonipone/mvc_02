<?php

session_start([
    'cookie_lifetime' => 86400,  // Impostazioni opzionali della sessione
    'read_and_close' => false,   // Evitare la scrittura se non necessario
]);

use \Core\{Config, Router, H};

// Definizione delle costanti
define('PROOT', __DIR__);  // Definisce la radice del progetto
define('DS', DIRECTORY_SEPARATOR);  // Separatore di directory, dipende dal sistema operativo

// Autoloading per le classi
spl_autoload_register(function($className) {
    $parts = explode('\\', $className);
    $class = array_pop($parts);  // Prende il nome della classe
    $path = strtolower(implode(DS, $parts));  // Costruisce il percorso della cartella
    $file = PROOT . DS . $path . DS . $class . '.php';

    // Verifica che il file esista
    if (file_exists($file)) {
        include($file);
    } else {
        // Aggiunge una gestione degli errori nel caso in cui il file non venga trovato
        throw new \Exception("File for class $className not found at $file");
    }
});

// Recupera la root directory dal file di configurazione
$rootDir = Config::get('root_dir');
define('ROOT', $rootDir);  // Definisce la costante ROOT per il percorso root del progetto

// Gestisce l'URL della richiesta
$url = $_SERVER['REQUEST_URI'];
$url = str_replace(ROOT, '', $url);  // Rimuove la parte ROOT dall'URL
$url = preg_replace('/(\?.+)/', '', $url);  // Rimuove le query string dall'URL

// Instrada la richiesta
Router::route($url);

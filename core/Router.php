<?php 

namespace Core;

use App\Controllers\Config;

class Router {
    
    // Tipizzazione dei parametri
    public static function route(string $url):  void {
        // Divide l'URL in parti
        $urlParts = explode('/', $url);

        // Setta il controller predefinito
        $controller = !empty($urlParts[0]) ? $urlParts[0] : Config::get('default_controller');
        $controllerName = $controller;

        // Aggiusta il nome della classe controller
        $controller = '\App\Controllers\\' . ucwords($controller) . 'Controller';

        // Imposta l'azione (metodo) predefinita come "index"
        array_shift($urlParts);
        $action = !empty($urlParts[0]) ? $urlParts[0] : 'index';
        $actionName = $action;
        $action .= 'Action';
        array_shift($urlParts);

        // Controlla se la classe controller esiste
        if (!class_exists($controller)) {
            throw new \Exception($controller . " - Controller Not Exist"); // Usa eccezioni in PHP 8
        }
        

        // Inizializza il controller
        $controllerClass = new $controller($controllerName, $actionName);

        // Controlla se il metodo (azione) esiste nel controller
        if (!method_exists($controllerClass, $action)) {
            throw new \Exception($action . " - Action Not Exist"); // Usa eccezioni in PHP 8
        }

        // Chiama l'azione con i parametri
        call_user_func_array([$controllerClass, $action], $urlParts);
    }

    // Tipizzazione del parametro e del tipo di ritorno
    public static function redirect(string $location): void {
        if (!headers_sent()) {
            header('Location: ' . ROOT . $location);
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href = "' . ROOT . $location . '"';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . ROOT . $location . '" />';
            echo '</noscript>';
        }
        exit();
    }
}

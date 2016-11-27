<?php
function gestionDesErreurs($type, $message, $fichier, $ligne)

{
	if (!defined('ERROR_CMS')) {
		define('ERROR_CMS', true);
	}

    switch ($type)

    {

        case E_ERROR:

        case E_PARSE:

        case E_CORE_ERROR:

        case E_CORE_WARNING:

        case E_COMPILE_ERROR:

        case E_COMPILE_WARNING:

        case E_USER_ERROR:

            $type_erreur = "Erreur fatale";

            break;


        case E_WARNING:

        case E_USER_WARNING:

            $type_erreur = "Avertissement";

            break;


        case E_NOTICE:

        case E_USER_NOTICE:

            $type_erreur = "Remarque";

            break;


        case E_STRICT:

            $type_erreur = "Syntaxe Obsolète";

            break;


        default:

            $type_erreur = "Erreur inconnue";

    }

    $erreur = '<pre>'.PHP_EOL;
	$erreur .= str_pad('', 100, '-',STR_PAD_RIGHT).PHP_EOL;
	$erreur .= str_pad('Date Time', 20, ' ',STR_PAD_RIGHT) .date("H:i:s").PHP_EOL;
	$erreur .= str_pad('Error Type', 20, ' ',STR_PAD_RIGHT) .$type_erreur.PHP_EOL;
	$erreur .= str_pad('Error Message', 20, ' ',STR_PAD_RIGHT) .$message.PHP_EOL;
	$erreur .= str_pad('Error Ligne', 20, ' ',STR_PAD_RIGHT) .$ligne.PHP_EOL;
	$erreur .= str_pad('Error File', 20, ' ',STR_PAD_RIGHT) .$fichier.PHP_EOL;
	$erreur .= str_pad('', 100, '-',STR_PAD_RIGHT).PHP_EOL;
	$erreur .= '</pre>'.PHP_EOL;

	if (ob_get_length() != 0) {
		ob_end_clean();
	}
	echo $erreur;
	die();
}


function gestionDesExceptions($exception)

{

    gestionDesErreurs (E_USER_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());

}


function gestionDesErreursFatales()

{

    if (is_array($e = error_get_last()))

    {

        $type = isset($e['type']) ? $e['type'] : 0;

        $message = isset($e['message']) ? $e['message'] : '';

        $fichier = isset($e['file']) ? $e['file'] : '';

        $ligne = isset($e['line']) ? $e['line'] : '';


        if ($type > 0) gestionDesErreurs($type, $message, $fichier, $ligne);

    }

}

error_reporting(0);
set_error_handler('gestionDesErreurs');
set_exception_handler("gestionDesExceptions");
register_shutdown_function('gestionDesErreursFatales');


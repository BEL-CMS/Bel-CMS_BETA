<?php 
function error_handler($t, $m, $f, $l, $all = false)
{
	switch ($t) {

		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_ERROR:
		case E_COMPILE_WARNING:
		case E_USER_ERROR:
			$c = "Erreur fatale";
		break;

		case E_WARNING:
		case E_USER_WARNING:
			$c = "Avertissement";
		break;


		case E_NOTICE:
		case E_USER_NOTICE:
			$c = "Remarque";
		break;


		case E_STRICT:
			$c = "Syntaxe Obsolète";
		break;

		default:
			$c = "Erreur inconnue";
		break;
	}

	$e  = '<pre>'.PHP_EOL;
	$e .= str_pad('', 100, '-',STR_PAD_RIGHT).PHP_EOL;
	$e .= str_pad('Date Time', 20, ' ',STR_PAD_RIGHT) .date("H:i:s").PHP_EOL;
	$e .= str_pad('Error Type', 20, ' ',STR_PAD_RIGHT) .$c.PHP_EOL;
	$e .= str_pad('Error Message', 20, ' ',STR_PAD_RIGHT) .$m.PHP_EOL;
	$e .= str_pad('Error Ligne', 20, ' ',STR_PAD_RIGHT) .$l.PHP_EOL;
	$e .= str_pad('Error File', 20, ' ',STR_PAD_RIGHT) .$f.PHP_EOL;
	$e .= str_pad('', 100, '-',STR_PAD_RIGHT).PHP_EOL;
	$e .= '</pre>'.PHP_EOL;

	if (ob_get_length() != 0) {
		ob_end_clean();
	}
	echo $e;
	//die($e);
}

function error_exceptions($e)
{
	error_handler (E_USER_ERROR, $e->getMessage(), $e->getFile(), $e->getLine(), $e);
}

function error_fatal()
{
	if (is_array($e = error_get_last())) {
		$type    = isset($e['type']) ? $e['type'] : 0;
		$message = isset($e['message']) ? $e['message'] : '';
		$fichier = isset($e['file']) ? $e['file'] : '';
		$ligne   = isset($e['line']) ? $e['line'] : '';

		if ($type > 0) error_handler($type, $message, $fichier, $ligne, $e);
	}
}
if (DEBUG === true) {
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	set_error_handler('error_handler');
	set_exception_handler("error_exceptions");
	register_shutdown_function('error_fatal');
} else {
	error_reporting(0);
}
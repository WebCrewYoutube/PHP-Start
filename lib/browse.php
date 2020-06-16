<?php

define('eol',PHP_EOL); # PHP_EOL to jest znak/znaki końca linii
define('LENLINE',48);
/** mój echo, e(1,2,3,"4-5-6"); Łamie wyświetlanie na LENLINE znaku
* @param ... (lista argumentów)
*/
function e() {
	$args = func_get_args();
	ob_start();
	foreach($args as $a) {
		if (is_array($a)) {
			echo "[*]";
		}
		else echo $a;
	}
	$s = ob_get_contents();
	ob_end_clean();
	$i=1;
	foreach (preg_split("//",$s) as $c) {
		if ($i % LENLINE == 0) {
			echo PHP_EOL, ($c!=PHP_EOL)?$c:"";
		}
		else {
			echo $c;
		}
		$i++;
	}
}

/** drow `line` ;) */
function hr($c="_",$n=55) {    echo "\n".str_repeat($c,$n) . "\n";  }  # element estetyczny
/**
 * browse($var,$method) is my version of displaying everything (almots)
 * @param mixed var = variable for analysis and display
 * @param int method = 0|1|2 functions: print_r(0) or var_export(1) or var_dump(2), default 1
 * @author Tomasz Jaśniewski <biuro@webjasiek.pl>
*/
function browse($var,$method=1,$separator=" ") {
	hr();
	if ($method==2) {
		ob_start(); // wyłącz wyjście
			var_dump($var); // nastąpi przechwycenie wyświetlania
			$cont = ob_get_contents(); // pobieram przechwycone dane
		ob_end_clean(); // czyszczę bufor i włączam wyjście
		echo preg_replace("/=>\n[ ]*/"," => ", $cont)."\n".str_repeat("-",55)."\n";
		return;
	}
   $m = ($method==1) ? "var_export" : "print_r";
   if (is_array($var) and count($var) and $method!=2) {
      while (true) {
         $e = current($var);
         if ($method==0)
            echo str_replace("\n",$separator, key($var)."=>[".$m($e,true)."]");
         else
            echo str_replace("\n",$separator, $m($e,true));
         if (next($var)!==FALSE) echo ",$separator";
         else {reset($var); break; }
      }
      echo "\n" . str_repeat("-",55);
   }
   else
      echo preg_replace("/[\n\r\f]+/",$separator, $m($var,true)) . "\n" . str_repeat("-",55);
   echo "\n";
}
?>
<?php
declare(strict_types=1); # typy wymagane
require_once 'lib/browse.php';
############################################################################
# 007 : Wyjątki, Błędy, Przechwyć i obsłuż.
# http://docs.php.net/manual/en/language.exceptions.extending.php
# http://docs.php.net/manual/en/spl.exceptions.php
# http://docs.php.net/manual/en/language.errors.php7.php
# http://docs.php.net/manual/en/reserved.exceptions.php
#

function example1 () {
	throw new Exception('Wyjątek z example1');
}

// example1(); // .. wyrzuci wyjątek


try { // kod próbuje wyłapać wyjątek
	example1();
} catch (Exception $ex) { // gdy wystąpi wyjątek, przechwytujemy
	echo $ex->getMessage(), "\n",
		$ex->getFile(), "\n",
		$ex->getLine(), "\n"; // linia z throw
		browse($ex,2);
} finally { // wykona się i tyle...
	echo "Finałowe echo.\n";
}

// wbudowane
function example2($arg) : int {
	return $arg;
}

//example2("problemik");
try {
	example2("problemik");
} catch (TypeError $ex) {
	echo "Typ się skawalił.\n";
}

// ArgumentCountError (+strict_types)
try {
	pow($base=2, "kłamstwo", $exp=3);
} catch (ArgumentCountError $ex) {
	echo "Błędna lista argumentów.\n";
}

// ArithmeticError
try {
	echo 0b11<<-1; // przesunięcie bitów za pomocą ujemnej liczby
} catch (ArithmeticError $ex) {
	echo "Błąd obliczeń.\n";
} catch (ArgumentCountError $ex) {
	echo "Multicatch!\n";
} catch (Error $ex) {
	browse($ex,2);
} catch (Exception $ex) {
	browse($ex,2);
}

try {
	fakeFunction(); // nie istnieje
} catch (Error $ex) {
	browse($ex,2);
}







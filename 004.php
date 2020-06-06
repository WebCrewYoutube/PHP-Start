<?php
############################################################################
# 004 : namespace, include/require
#
# Przestrzeń nazw to pewien sposób kapsułkowania elementów. Elementy nie mogą się powtarzać w ramach tej samej przestrzeni, ale mogą się powtarzać, gdy są w innej przestrzeni (kaspułce). Kapsułkowaniu podlegają funkcje, klasy czy choćby stałe, jednak nie zmienne;

require './encaps/ns1/f.php'; // podkatalog ./encaps/ns1/
require './encaps/ns2/f.php'; // podkatalog ./encaps/ns2/

# Uwaga! jestem w przestrzeni globalnej \

define('X',3);

function x() {
	echo "x() z aktualnej przestrzeni pliku 004.php\n";
}

Ns1\x(); # mogę wywołać funkcję x() z \Ns1
Ns2\x(); # mogę wywołać funkcję x() z \Ns2
Ns3\x(); # mogę wywołać funkcję x() z \Ns2

x(); # funkcja z przestrzeni globalnej
\x(); # \ - przestrzeń globalna jawnie

$o = new Ns2\EmptyClass(); #

echo Ns1\X, " " , Ns2\X, " ", X; // stałe z kolejnych przetrzeni
echo "\n", $varInside; # mimo zadeklarowania w \Ns1 i tak $varInside jest w przestrzeni globalnej!

# Dołączanie bibliotek.
# Istnieją 4 sposoby importowania :
# include, require, include_once, require_once
# include i require zasadniczo są identyczne, z tą różnicą, że require w razie problemów zwraca błąd krytyczny i uniemożliwia działanie programu, tymczasem include emituje ostrzeżenie i generalnie pozwala kontynuować skrypt.
# wersje z _once są identyczne do swoich krótszych kolegów, z tym, że sprawdzają, czy aby podobny plik nie był już importowany

//include 'error2.php';

//require 'error2.php';

//include 'lib/browse.php'; include 'lib/browse.php'; // redeklaracja !

//require 'lib/browse.php'; require 'lib/browse.php'; // redeklracaja !

//require_once 'lib/browse.php'; require_once 'lib/browse.php'; // i tak tylko raz !

//include_once './lib/browse.php'; include_once './lib/browse.php';

//require_once 'lib/browse.php'; include_once 'lib/browse.php'; // nie ma problemu

include './lib/browse.php';
browse([1,2,3,4,5,"koniec"]);

echo "\n\n\n Cudowna i zaawansowana instrukcja końcowa!\n\n\n";

?>
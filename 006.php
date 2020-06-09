<?php
namespace Domowe {
############################################################################
require_once 'lib/browse.php';
# 006 : autoloader, technika ładowania (include/require) bibliotek bez ręcznego ich podawania;
# Idea jest taka: pierwszy raz używam np. jakiejś klasy - w tle następuje załadowanie odpowiedniej biblioteki. Takie rozwiązanie bardzo ułatwia programowanie. PHP rozwija swój kod w trakcie wykonywania go, i w zależności od potrzeby.

############ AUTOLOADER #############
/*
Po co "includować" pliki ręcznie, gdy potrzeba dużo klas. Kod zaczyna wyglądać o tak:
include 'A.php';
include 'x/B.php';
include 'y/C.php';
include 'z/D.php';
include 'x/y/z/E.php';
itd.
Po pewnym czasie, gdy projekt rośnie, chcialibyśmy posiadać mechanizmy
automatycznego ładowania odpowiedniego pliku z odpowiednią klasą.
*/
spl_autoload_extensions(".php"); // koncentracja na imporcie plików *.php
spl_autoload_register(); // domyślne ładowanie w katalogu ./ (bieżącym)

$object = new \ExampleClass; // domyślnie szuka pliku ./ExampleClass.php
browse($object,2);


# Ale można zarejestrować własną funkcję ładującą z dowolnym pomysłem na realizację tej automatyzacji

define('CLASS_SUB_DIR','/cls/'); // moja stała w \

spl_autoload_register(
   function ($class_name) { # anonimowa funkcja ładująca
      require_once __DIR__.CLASS_SUB_DIR.$class_name.'.php';
   }
);
$object = new \ExampleClass2; // szuka ./cls/ExampleClass2.php
browse($object,2);

// $object = new SomeClass; // jaki błąd ? Brak pliku ComeClass.php w ./cls/, i w efekcie brak klasySomeClass, co powoduje Fatal error.

# STANDARDY ŁADOWANIA
/*
A co, kiedy wielu programistów porobi tysiące własnych autloaderów, z czego niektóre będą po prostu słabe i nieintuicyjne? Co będzie przy pracy nad wieloma projektami, w których pojawiać się będą wciąż inne autoładowania? Uciążliwością byłoby to ogarniać!

 * Najlepiej, jakby pewne elementy były takie same, niezależnie od projektu!
 * Szczegónie te, które mogą być takie same. Autoładowanie to właśnie taki mechanizm,
 * który w każdym projekcie może być taki sam!

I tu z pomocą przychodzą STANDARDY. Jednym z nich jest np. standard PSR-4 (zalecany),
który określa sposób autoładowania.
Wkrótce - gdy poznamy composer - załadujemy sobie gotowy mechanizm PSR-4 bez konieczności implementowania go.
*/

		'Ciekawostka';
# https://getcomposer.org/doc/04-schema.md#psr-4
# standard psr-4
/*
W skrócie:
 * Parowane są :		   prefix złożony z nazw przestrzeni  =>  ścieżka do katalogu z plikami
   np.:      \Vendor\SubNs1\SubNs2\ => /folder
 * Pierwsza przestrzeń w prefix'ie powinna być przestrzenią nazw dostawcy oprogramowania.
 * Ten sam prefix może mieć różne ścieżki, w których potem szukany będzie plik.
 * Ostatnia część ciągu przestrzeni nazw to nazwa klasy, musi istnieć plik Class.php.
 * Podprzestrzenie w prefix'ie, które nie są sparowane ze ścieżką do folderu, to w praktyce podkatalogi w tym folderze
 *
 * \Vendor\SubNs1\SubNs2\subf\subf\ExampleClass -> /folder/subf/subf/ExampleClass.php

Uwaga! Jeżeli wydaje Ci sie to skomplikowane, to zostaw to teraz.
Gdy z tego skorzystamy - wszystko stanie się jasne - już wkrótce.

Przykład 1:
 * \Vendor\X => ./lib/		| Sparowanie prefix -> ścieżka
 * \Vendor\X\Y_y			| klasa
 * Szuka pliku			| ./lib/Y_y.php

 * Przykład 2:
 * \Vendor\X => ./lib/		| Sparowanie prefix -> ścieżka
 * \Vendor\X\Y\Z			| klasa
 * Szuka pliku			| ./lib/Y/Z.php  ! Y to podkatalog ./lib/ a Z to klasa Z.php


 * WKRÓTCE zobaczycie to w działaniu, gdy poznamy COMPOSER ;)
 * Pozdrawiam.
*/
}
namespace Domowe\Zoo {
	define ('Domowe\Zoo\KOT',"żaba");
	browse($object,2); // browse jest w \
}
namespace { // przestrzeń \
	echo Domowe\Zoo\KOT;
}
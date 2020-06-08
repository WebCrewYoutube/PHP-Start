<?php
############################################################################
include_once 'lib/browse.php';

# 006 : autoloader !
############ AUTOLOADER #############
/*
Po co "includować" pliki ręcznie, gdy potrzeba dużo klas. Kod zaczyna wyglądać o tak:
include 'A.php';
include 'x/B.php';
include 'y/C.php';
include 'z/D.php';
include 'x/y/z/E.php';

Po pewnym czasie, gdy projekt rośnie, chcialibyśmy
posiadać mechanizmy automatycznego ładowania odpowiedniego pliku.
*/
spl_autoload_extensions(".php");
spl_autoload_register(); // domyślne ładowanie w katalogu ./
$object = new ExampleClass; // domyślnie szuka pliku ./ExampleClass.php
browse($object,2);

# można zarejestrować własną funkcję ładującą
spl_autoload_register(
   function ($class_name) { # anonimowa funkcja ładująca
      require_once __DIR__.'/cls/'.$class_name.".php";
   }
);
$object = new ExampleClass2; // szuka ./cls/ExampleClass2.php
browse($object,2);
// $object = new SomeClass; // jaki błąd ? Brak pliku, brak klasy.


#start:
# STANDARDY ŁADOWANIA
/*
   A co, kiedy wielu programistów porobi tysiące autloaderów? I niektóre będą po prostu słabe, nieintuicyjne, niewydajne? Co będzie przy pracy nad wieloma projektami? Ciągle uczyć się i starać zrozumieć inny mechanizm ładowania? Fajnie by było, gdyby pewne elementy (niezależnie od projektu) były takie same, nieprawdaż?
   Wtedy wchodzą na scenę STANDARDY. Jednym z nich jest np. standard PSR-4, który określa sposób ładowania. A narzędzie composer pomaga nam to zaimplementować. Ale o tym w nadchodzących odcinkach :)
*/
# https://getcomposer.org/doc/04-schema.md#classmap
# standard psr-4
/*
   Parowane są:
   prefix   =>    ścieżka
   np.:
   \Namespace1\Namespace2 => /folder1/folder2/.../folder

   Dla powyższej pary, jeżeli chcę użyć klasy:
   \Namespace1\Namespace2\Klasa
   autoload PSR-4 poszuka pliku /folder1/folder2/.../folder/Klasa.php
   albo chcę użyć klasy:
   \Namespace1\Namespace2\Cos\Klasa
   autoload PSR-4 poszuka pliku /folder1/folder2/.../folder/Cos/Klasa.php

   Ten sam prefix może mieć różne ścieżki, w których potem szuka pliku:
   \Namespace1\Namespace2 => /folder1
   \Namespace1\Namespace2 => /folder2

*/
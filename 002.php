<?php
function hr() {    echo "\n".str_repeat("_",55) . "\n";  }  # element estetyczny

############################################################################
# 002 : funkcje, zasięgi zmiennych, lambdy, referencje
# http://docs.php.net/manual/en/language.functions.php

# Funkcja charakteryzuje się składnią zbliżoną do takiej jak w C/C++ z tą różnicą, iż nie potrzebuje definiować zwracanego typu - zamiast tego korzystamy z instrukcji inicjującej definicję funkcji: function.

# Podobnie jak w C/C++ i wielu językach funkcja może coś zwracać, ale nie musi (return nieobowiązkowe!)

# Przeciążenie funkcji jak w C/C++ w zasadzie jest nieobecne/bezcelowe, bo z racji braku potrzeby definiowania typu zmiennej funkcje są polimorficzne 'z natury'


# za chwilkę wciągniemy te zmienne globalne do wnętrza funkcji ...
$globVar1=1;
$globVar2=2;

function fname($arg1,$arg2="default value",
   $arg3=["default","array"])
{
	$var = $arg1; # $var jest lokalna, zniknie z końcem działania funkcji
	echo "U are inside the fname function:\n";
	global $globVar1; // dostęp do globalnej zmiennej, wciągnęliśmy !
	echo $globVar1 . " " .
	$GLOBALS["globVar2"] . "\n"; // dostęp przez specjalną zmienną $GLOBALS
	$globVar1 = 10;
	$GLOBALS["globalVar"] = 2; // utworzę zmienną globalną !
	return $var;
}

echo fname(5) . "\n";
echo $globVar1, " ", $globalVar ;

hr();
$arrayVar = [1,3,5,"7"];
function f1($array) { // przekazanie przez wartość
   foreach ($array as $index => $element) {
      echo  $index . "->" . $element . " , ";
   }
   echo "\n";
   $array[0]=0;
}
f1($arrayVar);
echo $arrayVar[0]; // dalej 1
hr();

# funkcje mogą być definiowane wewnątrz bloków, innych funkcji itp. Przez co możemy uzyskiwać efekt "ładowania funkcji" z opóźnieniem:
function f2() {
   function insideF3() {
      echo "\twow efect!";
   }
}
# insideF3(); // nie zadziała bo nie wywołano jeszcze f2()
f2();
insideF3(); // zadziała :) wow !
hr();

# rekurencja oczywiście jest możliwa
function rn($arg1,$arg2) {
   echo "rn($arg1,$arg2)->";
   if ($arg1>=$arg2) {
      return "wake up...";
   }
   echo "inception!\n";
   return rn(++$arg1,--$arg2);
}
echo rn(10,20);

hr();

# referencja argumentów
function changeOrg(&$arg1) {
   $arg1="changed!";
}
$var = "dont change me";
changeOrg($var);
echo "\n" . $var . "\n";

# referencja przy zwracaniu
function &retRef() {
   static $var = ["jeden","dwa","trzy","cztery"];
   return $var;
}
$fromFunction = &retRef();
$fromFunction[3] = "osiem"; // w praktyce zmieniam statyczną $var z retRef !!!
echo retRef()[3] . "\n"; // osiem !

# jawne typy, niekiedy chcemy wymusić jakiś typ zmiennej
/*
   array, callable, bool (boolean), int, string, iterable, object
   wszystko się ujawni z czasem
*/
function retAr() : array { // wymuszam zwrócenie typu tablicowego
   //return 9; // takie coś nie jest array, więc wygeneruje wyjątek
   return [100,200];
}
echo retAr()[1] . "\n";

hr();

/**
 * yield (jak w Pythonie)
 * @param int n=0 : n-ty element ciągu fibonacci'ego
 * @return praktycznie zwraca zbiór/kontener z kolejnymi elementami ciągu fibonacc'iego
 */
function fibo($n=0) { // funkcja zwraca specjalny obiekt Generator
   yield $a=0;
   yield $b=1;
   for ($i=1; $i<=$n; $i++) {
      yield $a+$b;
      $temp = $a+$b;
      $a=$b;
      $b=$temp;
   }
}
foreach (fibo(8) as $step) { // fibo(8) - oblicza 8 el. ciągu nie licząc pierwszych dwóch
   echo $step . " -> ";
}
echo "\n";
hr();

#funckje anonimowe (lambdy/domknięcia) a w PHP mówi się na to Closure - DOMKNIĘCIE)
# występują jako argumenty, do funkcji można przypisać zmienną (callable)
# polecenie use() włącza zmienną z zakresu nadrzędnego (nie koniecznie globalnego!!)
$closure = function($arg1) use ($globVar1) {
   echo "Hi, $arg1! I like $globVar1. Do U remember the song 'Lamb(a)da'? ";
};
$closure("Widzu");
hr();
echo preg_replace_callback('/([0-9]+)([a-z]){1}/', function ($match) {
    return strtoupper($match[1].$match[2]);
}, '5a'); // zamienia napisy postaci 5a, 123c itp. na 5A, 123C itp.
hr();

# przydatne:
function giveMeMore($arg1,$arg2) {
   echo "\n" . $arg1 . $arg2 . "\n" .
   func_num_args() . " args, " .
   func_get_arg(0) . ", " .
   func_get_args()[1];
}
giveMeMore(1,2);
hr();

if (function_exists('giveMeMore')) { echo "\t yes, yes, yes!"; }
hr();

//var_dump($var,$globalVar,$arrayVar,$closure); // informacja o typie i wartości
//var_export($arrayVar); // wgląd w wartości

// natychmiast opuszcza program i zwraca komunikat
die("\n\nDie! (w wolnym tłumaczeniu: daj)\n\n");
$a=10;
echo $a;
?>



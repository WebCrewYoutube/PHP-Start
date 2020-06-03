<?php
/* PHP:   https://windows.php.net/download/   (php.exe)
 * Umieść ściągnięte pliki np. w katalogu c:\php7
 * Teraz polecenie
 *		c:\php7\php.exe plik_z_kodem.php
 * uruchomi nasz kod zawarty w pliku *.php
 * (warto dodać położenie pliku do PATH, aby uruchamiać kod poleceniem php plik_z_kodem.php)
*/
/* IDE:   https://netbeans.apache.org/download/index.html
 * można powiązać pobrany interpreter php.exe (w moim przypadku kod po lewej uruchamiam za pomocą SHIFT+F6 a wyniki działania programu są wyświetlone na konsoli po prawej stronie)
 */

/* Kilka uwag o języku PHP, kilka porównań */
// niski `próg wejścia`
# Wykorzystywany w budowie aplikacji z wykorzystaniem przeglądarki (oprogramowanie po stronie serwera, backend)
# Obiektowy język interpretowalny (jak Python)
# Nie ma konieczności typowania zmiennych (jak w C++), dużo niejawnych konwersji, sporo intuicyjności jak w Pythonie, wyglądem trochę podobny (w mojej subiektywnej ocenie) do C/C++/Perl
# Nie ma wskaźników jak w C/C++ (referencje jak w Python)
# Podobnie jak w języku Python można o zmiennych myśleć jak o referencjach; (W przypadku PHP można myśleć o nich jak o dowiązaniach twardych w systemie plików Unix).
# Czasami mówi się, że PHP ma pewne ograniczenia, a czasami pozwala na zbyt dużo, co bywa powodem tworzenia chaotycznego kodu (to jednak wina programisty?). Moim skromnym zdaniem nie ma języka, który nadaję się do wszystkiego i jest najlepszy we wszystkim. PHP jest świetny w pewnych zastosowaniach i tam jego ograniczenia/swobody bywają atutem. Wiele zależy od kontekstu, nieprawdaż? Wielki facet może być atutem, ale nie w czołgu... A tak poza tym język wciąż się rozwija i moim zdaniem jego odsłony w wersji 7.x są po prostu zmianami w dobrą stronę.
# PHP ma swoją społeczność, composer do zarządzania pakietami, swoje framework'i i wiele aktywnie wykorzystywanych rozwiązań. Wiele popularnych serwisów `stoi` na PHP'ie i ma się dobrze.
###########################################################################################
# UWAGA! Nie tłumaczę podstaw programowania, ale pokazuję język PHP, zakładając, że istotę programowania pojęliście przy okazji nauki innego języka, np. C++ albo Python. Jest to więc materiał dla osób, które podstawy programowania w tym programowania obiektowego mają za sobą.
###########################################################################################
# 001 : podstawowe typy, zmienne, instrukcja kontrolujące kod (ify,pętle), operatory
      //Wszelakie OPERATORY są prawie identyczne do C/C++
# http://docs.php.net/manual/en/language.types.php
# http://docs.php.net/manual/en/language.variables.php
# http://docs.php.net/manual/en/language.operators.php
# http://docs.php.net/manual/en/language.control-structures.php

$var = 10; // nie ma żadnej wstępnej deklaracji, bach! bach! i jest
echo $var , "\n"; // po przecinku wypisz kolejne elementy

$var = 10.123;
echo $var . "\n"; // kropeczka to konkatenacja napisów
echo ($var * 2) . "\n"; // $var nie przestaje być liczbą !

$var = 0xff; // hex  (255)
echo $var . "\n";

$var = 017; // oct (15)
echo $var . "\n";

$var = 0b111; // binarinie (7)
echo $var . "\n";

$var = "text" . ' other text'; // zmiana typu $zmiennej? Nie ma problemu. Kropeczka to konkatenacja napisów
echo $var . "\n";
/*
   napis " ... " interpretuje zmienne i znaki (a przynajmniej próbuje)
   napis ' ... ' zostawia wszystko jak leci
*/
$x=1;
echo "\"->$var, {$x}\n"; // \" pozwala wyświetlić "
echo '\'->$var, {$x}\n' . "\n"; // \' pozwala wyświetlić '

# INSTRUKCJA WARUNKOWA, wyrażenie warunkowe, switch i ... ??
if ($var[0] == "t") echo "!\n";
else {
   // oczywiście sam if, oraz else if/elseif działa ;)
}

echo ( ($var[0]!="t") ? "A" : "B"  ) . " (wyrażenie warunkowe)\n";

switch ($var) {
   case "text other text":
      echo "option 1\n";
      break;
   case "other":
      echo "option 2\n";
      break;
   default:
      echo "option default\n";
}

# ?? - czyli ciekawy operator
if ($var3==null) echo "null\n";
$var2 = $var3 ?? 10; // czym będzie $var2 ?
echo $var2 . " :-O\n"; // będzie ... 10-ką
$var3 = "istnieję";
$var2 = $var3 ?? 10; // czym będzie $var2 ?
echo $var2 . " :-)\n"; // będzie ... 10-ką bo $var3 istnieje...
// można też :
if (isset($var4)) $var2 = $var4;
else echo '$var4 nie istnieje'."\n";


# PĘTLE (for, while, do..while, foreach)

for ($i=1; $i<10; $i++) {
   echo $i .", ";
}
echo "\n";

for ($w=10, $k=2; $w>=1 and $k>=1; $w--) {
   echo $w . ", ";
   if ($w==1) {
      $k--;
      $w=11;
      echo "\n";
   }
}

$counter=15;
while ($counter--) echo $counter . ", ";
echo "\n";

do {
   $counter+=2; // zwiększ o 2
   echo $counter . ", ";
} while ($counter<8);
echo "\n";

$var = [$var,100,111,222]; // [   ] > tablica :)
echo $var[1] . "\n"; // jak tablica w C
foreach ($var as $v) { // foreach pozwala iterować po elementach
   echo $v . ", ";
}
echo "\n";

$var = [ "key"=>"value", 200=>"other value" , 2000=>1];
foreach ($var as $k=>$v) {
   echo $k . " -> " . $v . "\n";
}
$var = [ "key"=>"value", ["key2"=>"value2", 1=>2]]; // tablica w tablicy? Proszę bardzo.

#referencje
$var5 = 10;
$var6 = &$var5; //$var5 i $var6 wskazują tę samą treść/wartość
echo $var6 ."\n";
$var6 = "wartość var6"; // w var5 nie będzie już 10 !
echo $var5 . " (&)\n"; //
unset($var5); // kasuję $var5 ale nie znika wartość, bo istnieje jeszcze referencja $var6 do tej wartości, dlatego poniższa instrukcja wciąż wskaże wartość
echo $var6 . " (po usunięciu var5)\n";
unset($var6); // teraz nie ma już nic

foreach ($var as $k=>&$v) {    $v=0;    } //
foreach ($var as $k=>$v) {
   echo $k . " => " . $v . "\n";
}
// bo
$yolo=[1,2,3];
foreach ($yolo as &$e) $e=10;
foreach ($yolo as $e) echo $e,", ";
echo "\n";


# różne takie ciekawostki i czary

$magic = "var";
echo ${$magic}["key"] . "\n";
echo $$magic["key"] . "\n";

# jeżeli będziemy uruchamiać PHP z opcją
# declare(strict_types=1);
# wtedy musimy określać typ jak w przypadku C/C++
# declare(strict_types=1); // MUSI BYĆ NA POCZĄTKU SKRYPTU
# int $a, string $b

?>

Hello World ? Pleas, dont 'hello world' me!
<a href=":)"> :-{ </a>

<?php if (true) : ?>
****
This is PHP Code? No.
****
<?php else : ?>

It's not true.

<?php endif; ?>



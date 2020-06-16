<?php
require_once './lib/browse.php';
############################################################################
# 013 : Poćwiczmy język PHP! (w trybie konsoli, CLI)
# Przy okazji poznamy nowe funkcje.
# Proszę - korzystajcie śmiało z dokumentacji języka. W moich plikach znajdziecie
# dużo linków do funkcji w PHP.
# I nie bójcie się explorować, przeglądajcie dostępne funkcje/metody/klasy.
#
# Zadanie 1
# Policz wystąpienia każdego znaku w napisie $chars. Uważaj na polskie znaki.
# (może się przydać) https://www.php.net/manual/en/ref.mbstring.php
$chars=<<<CHARS
ojdkqazwmcmndbvoeripooqiuyituvjfhl
amnbzxcvbnkashfarposdifaqjfbłńąśżźć
CHARS;
# Uwaga!
# problemem stają się niekiedy polskie znaki!
$str1 = "Ola";
$str2 = "Ołą";
hr();
echo strlen($str1), " ", strlen($str2), "\n"; # 3 i 5! Niektóre znaki są liczone x2 gdyż zajmują więcej pamięci (nie zajmują jednego bajtu, ale więcej). strlen liczy bajt jak jeden znak!
# Na szczęście podstawowe funkcje w PHP, które obsługują napisy, mają swoją wersję
# mb_* (multibyte string)
echo mb_strlen($str1), " ", mb_strlen($str2), "\n"; # już OK

# ROZWIĄZANIE
$letters = mb_str_split($chars); // mb_str_split
// ALBO
function get_chars(&$text) {
	for ($i=0; $i<mb_strlen($text); $i++)
		yield mb_substr($text, $i, 1); // pobieram podciąg
}
// $letters = get_chars($chars);



echo mb_strlen($chars),"\n"; // 77 znaków w napisie $chars
$licz=[];
foreach ($letters as $char) {
	$licz[$char] ??= 0;
	$licz[$char]++;
}
$suma = 0;
$ile=1;
foreach($licz as $char=>$count) {
	echo $char,"=>",$count, (++$ile%6==1) ? E : "\t";
	$suma+=$count;
}
hr();
echo $suma;
# Uwaga! Ponieważ znakiem końca linii nie jest wcale jeden znak...
browse(PHP_EOL,2); # ... dlatego enter policzony jest jakby dwa razy ...
# ... zatem "\n" != PHP_EOL
browse("\n",2);
echo (PHP_EOL === "\r\n"), PHP_EOL; // tym jest enter



# Zadanie 2
# W pliku "dane013.txt" znajduje się 12 kolumn z liczbami (300 wierszy). Liczby
# oddzielone są znakiem "\t" (tabulacja).
# a) Znajdź największą wartość dla każdego parzystego wiersza i najmniejszą wartość dla każdego nieparzystego wiersza
# b) Oblicz średnią dla każdej kolumny
# c) Uporządkój średnie z b) rosnąco. Zapamiętaj uporządkowane średnie, jednak w taki sposób, aby w każdej chwili można było sprawdzić, dla której kolumny jest to średnia.
# d) Znajdź najdłuższy podciąg rosnący (kolejne liczby rosną) w ciągu liczb złożonym z wszystkich wierszy (300 wierszy traktuj jak jeden długi ciąg liczb). Najdłuższy podciąg umieść w pliku "pod013.txt". Jeżeli znajdziesz więcej najdłuższych podciągów o takiej samej długości, wybierz ten, którego suma liczb jest największa. Jeżeli i tu byłoby kilka o tej samej sumie liczb, wybierz pierwszy znaleziony spośród nich.
#

# Zadanie 3
# Utwórz plik dane013hex.txt na podstawie pliku dane013.txt, w którym kolejne liczby są zapisane w postaci szesnastkowej o ile były to liczby parzyste, a nieparzyste zapisz w postaci binarnej.
#


# Zadanie 4 (swobodny projekt)
# W przestrzeni nazw Potwory zaprojektuj Klasy reprezentujące Orków i Elfy. Powinny zawierać takie własności jak zdrowie, siłę, obronę i prędkość. Zaprojektuj metodę ataku, gdzie obiekt reprezentujący Orka i obiekt reprezentujący Elfa wzajemnie się zaatakuję (jeden raz) w taki sposób, by zostały wykorzystane wszystkie własności;
# Niech ork i elf walczą aż do śmierci (zdrowie = 0); Wizualizuj kolejne rundy ataków (w trybie tekstowym oczywiście);
# Uwaga! Plik z klasami powinien być oddzielny (do wykorzystania jako biblioteka),
# a sama walka powinna być zdefiniowana w innym pliku, który korzysta z pliku z klasami. Wykorzystaj autoładowanie klas.
#

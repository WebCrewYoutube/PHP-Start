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
# https://www.php.net/manual/en/ref.strings.php
#
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



echo mb_strlen($chars),"\n"; // 71 znaków w napisie $chars
$licz=[];
foreach ($letters as $char) {
	$licz[$char] ??= 0;
	$licz[$char]++;
}
$suma = 0;
$ile=1;
foreach($licz as $char=>$count) {
	echo $char,"=>",$count, (++$ile%6==1) ? eol : "\t";
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
# https://www.php.net/manual/en/book.filesystem.php
# https://www.php.net/manual/en/ref.array.php
# https://www.php.net/manual/en/book.mbstring.php
# https://www.php.net/manual/en/book.strings.php
# https://www.php.net/manual/en/ref.strings.php
#
# W pliku "dane013.txt" znajduje się 12 kolumn z liczbami (300 wierszy). Liczby
# oddzielone są znakiem "\t" (tabulacja).
# a) Znajdź największą wartość dla każdego parzystego wiersza i najmniejszą wartość dla każdego nieparzystego wiersza
# b) Oblicz średnią dla każdej kolumny
# c) Uporządkój średnie z b) rosnąco. Zapamiętaj uporządkowane średnie, jednak w taki sposób, aby w każdej chwili można było sprawdzić, dla której kolumny jest to średnia.
# d) Znajdź najdłuższy podciąg rosnący (kolejne liczby rosną) w ciągu liczb złożonym z wszystkich wierszy (300 wierszy traktuj jak jeden długi ciąg liczb). Najdłuższy podciąg umieść w pliku "pod013.txt". Jeżeli znajdziesz więcej najdłuższych podciągów o takiej samej długości, wybierz ten, którego suma liczb jest największa. Jeżeli i tu byłoby kilka o tej samej sumie liczb, wybierz pierwszy znaleziony spośród nich.
#
#
#
#
#
#
#
#
#
#
#
# Można tak:
# ODCZYT do tablicy dwuwymiarowej
$fd = fopen("dane013.txt","r");
$data = [];
while ($row = fgetcsv($fd, 0, "\t")) {
	$data[]=array_map(fn(&$e)=>(int)$e,$row);
}
// browse($data,2); // jak chcesz, to zobacz, że $data to tablica dwuwymiarowa
fclose($fd);
# a) Znajdź największą wartość dla każdego parzystego wiersza i najmniejszą wartość dla każdego nieparzystego wiersza
for ($i=0; $i<count($data); $i++) { # idę przez wiersze wg indeksu
	if ($i%2==1) { // parzysty wiersz, chociaż nieparzysta numeracja!
		e("Wiersz ",$i+1," => max=",max($data[$i])," ",eol);
	} else { // nieparzysty wiersz
		e("Wiersz ",$i+1," => min=",min($data[$i])," ",eol);
	}
}
# oczywiście wyszukiwanie mix/max można zastąpić swoją funkcją :-)








# b) Oblicz średnią dla każdej kolumny
$avg=[];
$rows = count($data);
$cols = count($data[0]);
for ($c=0; $c<$cols; $c++) {
	for ($r=0; $r<$rows; $r++) {
		$avg[$c]??=0;
		$avg[$c]+=$data[$r][$c]; // $c -> kolumna
	}
}
array_walk($avg,fn(&$e)=>$e=$e/$rows);
browse($avg,2);

# c) Uporządkój średnie z b) rosnąco. Zapamiętaj uporządkowane średnie, jednak w taki sposób, aby w każdej chwili można było sprawdzić, dla której kolumny jest to średnia.
asort($avg,SORT_NUMERIC);
browse($avg,2);

# d) Znajdź najdłuższy podciąg rosnący (kolejne liczby rosną) w ciągu liczb złożonym z wszystkich wierszy (300 wierszy traktuj jak jeden długi ciąg liczb). Najdłuższy podciąg umieść w pliku "pod013.txt". Jeżeli znajdziesz więcej najdłuższych podciągów o takiej samej długości, wybierz ten, którego suma liczb jest największa. Jeżeli i tu byłoby kilka o tej samej sumie liczb, wybierz pierwszy znaleziony spośród nich.











$all = file_get_contents("dane013.txt");
$strall = str_replace(["\t","\n",eol,"\r\n","\r"]," ",$all);
file_put_contents("dane013-all.txt",$strall); //3600 liczb !
$all = mb_split(" ",$strall);
$isAsc = function (array &$arr, int $from, int $to) : bool {
	for($i=$from; $i<$to; $i++)
		if($arr[$i]>=$arr[$i+1]) return false;
	return true;
};
$maxIndex = count($all) - 1;
for ($big = $maxIndex; $big>=1; $big--) {
	$candidate = [];
	for($from=0; $from+$big<=$maxIndex; $from++) {
		if($isAsc($all,$from,$from+$big)) {
			$candidate[] = array_slice($all,$from,$big+1);
		}
	}
	if (count($candidate)) {
		if(count($candidate)==1) {
			e("WYNIK TYLKO JEDEN",eol);
			browse($candidate[0],2);
			file_put_contents("pod013.txt",
				implode(' ',$candidate[0]));
		} else {
			$index = 0;
			$sum = array_sum($candidate[$index]);
			for ($i=1;$i<count($candidate);$i++) {
				if ($sum < array_sum($candidate[$i])) {
					$sum = array_sum($candidate[$i]);
					$index = $i;
				}
			}
			e("WYNIKI",eol);
			browse($candidate,2);
			hr();
			e("INDEX = $index",eol);
			browse($candidate[$index],1);
			file_put_contents("pod013.txt",
				implode(' ',$candidate[$index]));
		}
		break; # znalezione zachłannie, nie ma co więcej szukać
	}
}

# Zadanie 3
# Utwórz plik dane013hexbin.txt na podstawie pliku dane013.txt, w którym kolejne liczby są zapisane w postaci szesnastkowej o ile były to liczby parzyste, a nieparzyste zapisz w postaci binarnej.
# https://www.php.net/manual/en/book.math.php
#
#
#
#
#
#
#
#
#
#
#
#
#
#
#
$fd=fopen("dane013hexbin.txt","w");
$step=1;
foreach ($all as $number) {
	fputs($fd,((int)$number%2==1) ? base_convert("$number",10,2) : base_convert("$number",10,16));
	fputs($fd,($step%12==0) ? eol : "\t");
	$step++;
}
fclose($fd);








# Zadanie 4 (swobodny projekt)
# W przestrzeni nazw Potwory zaprojektuj Klasy reprezentujące Orków i Elfy. Powinny zawierać takie własności jak zdrowie, siłę, obronę i prędkość. Zaprojektuj metodę ataku, gdzie obiekt reprezentujący Orka i obiekt reprezentujący Elfa wzajemnie się zaatakuję (jeden raz) w taki sposób, by zostały wykorzystane wszystkie własności;
# Niech ork i elf walczą aż do śmierci (zdrowie = 0); Wizualizuj kolejne rundy ataków (w trybie tekstowym oczywiście);
# Uwaga! Plik z klasami powinien być oddzielny (do wykorzystania jako biblioteka),
# a sama walka powinna być zdefiniowana w innym pliku, który korzysta z pliku z klasami. Wykorzystaj autoładowanie klas.
#

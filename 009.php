<?php
require_once 'lib/browse.php';

############################################################################
# 009: Usługi wszelakie na przykładzie : cURL, JSON
# PHP pomaga korzystać z różnych usług/technologii, które wspiera odpowiednimi bibliotekami/funkcjami :
# posiada np. wsparcie dla języków naturalnych, wsparcie poczty,
# kontrolę procesów, manipulację grafiką 2D, pakowanie (kompresja jak np. .zip),
# kryptografię i naprawdę wiele innych, patrz:
#		http://docs.php.net/manual/en/funcref.php
# Uwaga! PHP nie dostarcza samym usług, ale zakłada ich istnienie. Czasami ktoś pyta: "hej, dlaczego nie działa mi polecenie wysyłania poczty w PHP ?!?"
# odpowiadam: "widocznie w twoim systemie nie ma usługi wysyłania poczty (lub innej którą chcesz obsługiwać), do której PHP mógłby się odwołać, ewentualnie taka usługa jest, ale PHP nie ma odpowiedniej konfiguracji dostępowej dla tej usługi (np. usługa pracuje na niestandardowym porcie, albo nie wiadomo w jakim katalogu są pliki powiązane z usługą itp. itd.)"
# Tak niestety jest. Samo programowanie to narzędzie. Aby je wykorzystać, trzeba rozumieć wiele innych technologii i usług. Nie zawsze trzeba je znać od podszewki, ale przynajmniej rozumieć ich istotę i znać podstawowe sposoby konfiguracji. Wtedy i tylko wtedy będziemy mogli użyć naszego narzędzia (PHP) i jego bibliotek do obsługi tych różnych funkcjonalności.
# Przykład: Technicznie można umieć jeździć samochodem, ale żeby faktycznie jeździć, trzeba się jeszcze nauczyć: co to jezdnia a co to chodnik, co to światła, co to fotoradar, co to znaki drogowe i jak wygląda policja. Inaczej zdolność jazdy prosto a nawet w lewo i w prawo i tak nie pozwoli korzystać z uroków ulubionego fiata 126p.
# ... just ...


# # Dzisiaj wybrałem dwie biblioteki: CURL i JSON
# https://www.php.net/manual/en/book.curl.php
# https://www.php.net/manual/en/book.json.php



################ cURL (client URL) #################
# https://en.wikipedia.org/wiki/CURL
# https://pl.wikipedia.org/wiki/CURL

$c = curl_init();
curl_setopt_array($c, [
	CURLOPT_URL=>'webjasiek.pl',
	CURLOPT_HEADER=>true,
	CURLOPT_NOBODY=>true,
]);
# różne opcje połączenia : http://docs.php.net/manual/en/function.curl-setopt.php
$result = curl_exec($c);
if ($result && !curl_errno($c)) {
	browse(curl_getinfo($c),2);
}
// abu curl_exec nie wyświetlał ale by dało się to przechwycić można skorzystać
// z ob_start(); ob_clean();

ob_start(); // wyłącz wyjście
curl_exec($c); // nie wyrzuci na wyjście tylko zbuforuje
$response = ob_get_contents(); // pobieram z bufora
ob_end_clean(); // czyszczę bufor i włączam wyjście
echo "CAPTURED: \n\n" . $response;

############### JSON (JavaScript Object Notation) #################
# https://pl.wikipedia.org/wiki/JSON

class Mutant extends Stdclass {
	public string $name;
	public function __construct(string $n) {
		$this->name = $n;
	}
}
$mutant = new Mutant("Supermutant from Vegas");
$source = [
	"name"=>"Tomasz",
	"lastname"=>"Szamot",
	"pets"=>[
		"chinchillas"=>["Gustaw","Zdzisław"],
		"parrots"=>["Gucio","Maja"],
	],
	"pets_status"=>[
		"chinchillas"=>["Gustaw"=>true, "Zdzisław"=>true],
		"parrots"=>["Gusio"=>false, "Maja"=>false],
	],
	"secret"=>$mutant,
	"number"=>62456176245613562856274571783,
];
$jsoncode = json_encode($source); // $source -> string
echo $jsoncode;

// true - tablica asocjacyjna, false - Stdclass
$decode = json_decode($jsoncode, false, 4);// liczba to głębokość rekurencji
hr();
echo $decode->lastname,"\n";
browse($decode,2);

echo json_last_error_msg(), "\n"; // jakiś error-problem??

// json_decode ma kilka opcji, jak np. JSON_BIGINT_AS_STRING
$decode = json_decode('{"number": 62456176245613562856274571783}', true, 512, JSON_BIGINT_AS_STRING);
hr();
echo $decode["number"];
browse($decode,2);


# Ciekawostka off-topic na koniec. Jeden z widzów zauważył, że instrukcja break w PHP, które wychodzi z pętli, może mieć atrybut wyjścia z konkretnej ilości pętli. Przykład:

# break bez poziomu wyjścia
$ex1 = [1,2,3,4];
$ex2 = [10,20,30,40];
foreach ($ex1 as &$a) {
	foreach ($ex2 as &$b) {
		echo $b," ";
		break; // bez break byłoby 10..40 1 10..40 2 10..40 3 10..40 4
	}
	echo $a," ";
} # wyświetli : 10 1 10 2 10 3 10 4
echo "\n";
# break z poziomem 2, czyli opuści dwie pętle: obecną, i tę o poziom wyżej.
foreach ($ex1 as &$a) {
	foreach ($ex2 as &$b) {
		echo $b," ";
		break 2; // opuści natychmiast pierwszą pętlę foreach
	}
	echo $a," ";
} # wyświetli tylko : 10
hr();
# continue też to obejmuje!
foreach ($ex1 as &$a) {
	echo $a," "; // tylko to się wykonuje
	foreach ($ex2 as &$b) {
		continue 2; // natychmiast idzie do następnego kroku pierwszej pętli
		echo $b," "; // nigdy się nie wykona
	}
	echo $a, " "; // nigdy się nie wykona

} # wyświetli : 1 2 3 4
hr();

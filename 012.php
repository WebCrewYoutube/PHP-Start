<?php
namespace A {
include_once 'lib/browse.php';
############################################################################
# 012 : Jeżeli znasz PHP, a tylko zasiedziałeś się w wersjach przed PHP 7.x ...
/*
### KOLEJNE ZMIANY od wersji 7.0 do 7.4
 * Zmiany w 7-ce to poprawiona mocno wydajność, poprawione nieścisłości i błędy. Wybrałem to, co wydaje mi się subiektywnie najbardziej przydatne. Podkreślam - subiektywnie!
 * https://www.php.net/manual/en/migration70.php
 *
### Kilka zmian wstecznie niekompatybilnych:
 * https://www.php.net/manual/en/migration70.incompatible.php
 *	set_exception_handler() : nie ma gwarancji odbiera obiekty wyjątków
 *	zmiena interpretacji przy zapisach:
 *		zapis				php 5				php 7
 *		$$foo['bar']['baz']	${$foo['bar']['baz']}	($$foo)['bar']['baz']
 *		$foo->$bar['baz']		$foo->{$bar['baz']}	($foo->$bar)['baz']
 *		$foo->$bar['baz']()	$foo->{$bar['baz']}()	($foo->$bar)['baz']()
		Foo::$bar['baz']()	Foo::{$bar['baz']}()	(Foo::$bar)['baz']()
 *	list() - nie może być pusta, więc tak nie wolno: list()=$x;
 *	list() - nie rozpakuje napisu, użyj str_split()
 *	foreach `by-value` działa na kopii tablicy !!!
*/
$array=[1,2,3];
foreach($array as $val) $val++; // kopia
browse($array); // 1,2,3 ! nie zmieniło zawartości
/*
 *	foreach: - nie zmienia wewnętrznego wskaźnika iteracji w tablicach
 */
foreach ($array as &$val)
    echo $val,"-",current($array),"\t";
hr();
//a tu przykład użycia iteracji po wskaźnikach wewnętrznych w tablicy
while($e = current($array)) { // $e to kopia elementów
	echo $e,"->";
	$e=20;
	next($array); // iterowanie po wskaźniku iteracji
}
hr();
reset($array);
browse($array);
/*
 *	foreach: - dodanie elementu podczas iteracji jest "wykryte" (w przypadku `by-reference`)
 */
$array=[1,2,3];
foreach($array as &$val) { // dla $val nie zadziała
	echo $val,", ";
	$array[3]=10; // ustanawia kolejny element
	$array[1]=20; // ustanawia kolejny element
} // 1,2,3 -> 1,20,3,10 !!

hr();
// rozpakowanie za pomocą [] wewnątrz foreach:
$family = [['tata','Zbyszek'],['mama','Hania']];
foreach ($family as [$code,$name]) echo $code, " " , $name ,"\n";

// w list() albo przy rozpakowaniu [] mogę posłużyć się kluczami
$family = [
	['tata'=>'Zbyszek', 'mama'=>'Hania'],
	['tata'=>'Franio', 'mama'=>'Zenia']
];
foreach ($family as ['mama'=>$name1,'tata'=>$name2]) {
	echo $name1, " vs ", $name2 , "\n";
}


/*
*	zmiana: CIĄGI tekstowe zawierające liczby szesnastkowe nie są traktowane jako liczby!
 */
hr();
echo "0xff" + 0 ,"\n";
// ale można zrobić tak:
$int = filter_var("0xff",FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX);
echo $int , "\n";
/*
 *	usunięto call_user_method(), call_user_method_array()
 *	usunięto aliasy mcrypt()
 *	usunięto ext/mysql, ext/mssql, ext/sybase_ct, ext/ereg
	*	zamiast `ereg`. PCRE jest zalecaną alternatywą.
 *	usunięto set_socket_blocking()
 *	wynik operatora new nie może być przypisany przez referencję
 *		$o = &new Class; // error
 *	yield ma zmieniony priorytet (między print a =>)
 *	switch: nie może mieć wielu bloków domyślnych
 *	pliki .ini nie moga używać # jako komentarz, powinno się używać ;
 *	Zmiany w JSON : https://www.php.net/manual/en/ref.json.php
 */

hr();
### Nowości: https://www.php.net/manual/en/migration70.new-features.php
// typy podstawowe, skalarne: int, float, string, array, callable, bool
function all(int ...$attr) { # folding
	foreach($attr as &$a) echo $a, ", ";
}
all(1,2.5,"3",'3.5'); // 2.5 i 3.5 obcięte do int
echo "\n";

// deklaracja zwracanego typu
function sum(float ...$as) : float {
	return array_sum($as);
}
echo sum(1,2,3,44.44), "\n";

// jeżeli dodam ? przy zwracanym typie dopuszczony jest null jako zwracana wartość
function sum2(...$as) : ?float {
	foreach ($as as $a) if (!is_numeric($a)) return null;
	return array_sum($as);
}
echo sum2(1,2,3), " [", sum2(1,2,"crash?") === NULL, "]\n";

// jeżeli zwrócę void, mogę użyć return; bez zwracania czegokolwiek.
// null to nie nic !!!
function nothingness() : void { return; }
browse(nothingness(),2); // to teraz taka jawna procedura

// operator ??
// pamiętacie z PHP 5 takie zapisy ?
$user = isset($_GET['user']) ? $_GET['user'] : 'nobody';
// teraz można krócej:
echo $user = $_GET['user'] ?? 'nobody' ,"\n";
// można ?? łączyć
echo $no1 ?? $no2 ?? "?? is fun\n"; // wyświetli się pierwsze, co istnieje ;)

// operator <=> : porównanie (zwraca -1 gdy mniejsze, 1 gdy większe 0 jak równe)
browse([	5 <=> 10 , 5 <=> 0, 5 <=> 5		]);

// jako stałe, można zdefiniować tablice
define('DOGS',['Mada','Rtoip']);
echo DOGS[0],"\n"; // 'Mada'

// anonimowe klasy
$o = new class {
	static public function show() {
		echo "inside anonymous you will find a mous(e)\n";
	}
};
$o->show();
$o::show();

// Unicode
echo "\u{61}  \n"; // 61 hex -> 97 int -> 'a'
echo "lubię \u{ab} strzałki"; // fajne nie?
hr();

// Closure::call() -> funkcja spoza klasy przywołana, jakby była częścią klasy
class X { private $x="I am `x`!\n"; }
$getX = function() { echo $this->x; };
$o = new X;
$getX->call($o); // na obiekcie $o klasy X wywołam anonimową funkcję jakby była z wnętrza klasy X. Ma dostęp nawet do prywatnej właściwości $x !

// grupowanie use

/* przed 7:
	use some\namespace\ClassA;
	use some\namespace\ClassB;
	use some\namespace\ClassC as C;
 * po 7.0
	use some\namespace\{ClassA, ClassB, ClassC as C};
 */

// return + yield (return na końcu generatora, po yield'ach)
function gene() {
	$a=1; $b=2; $c=3;
	yield $a; yield $b; yield $c;
	return $a+$b+$c;
}
$result = gene();
foreach ($result as $elem) echo $elem,", "; // 1,2,3
echo $result->getReturn(); // 6


// nowa funkcja: intdiv (dzielenie całkowite)
browse([intdiv(10,3), 10/3]);

// Gdy metoda w klasie nie jest jawnie statyczna (static) nie można jej już przywołać:
class Y {
	public function ups(){}
	public static function welldone(){}
};
Y::welldone(); // tak wolno :)
// Y::ups(); // tak szybko :( czyli tak nie wolno!


# W klasach stałe mogą mieć specyfikację public, private, protected
$c = new class { public const PROCONST = 10; }; // anonimowa klasa
echo "const=", $c::PROCONST, "\n";

// nowy typ: iterable (coś po czym da się iterować). coś, co jest iterable musi implementować
// Travesable https://www.php.net/manual/en/class.traversable.php
function IcanIterate(iterable $ivar) {
	foreach ($ivar as $element) { }
}

// nowy typ: object
function newType() : object {
	return new \StdClass();
}
browse($o=newType(),2);


// multi-catch dzięki | (to pionowa kreseczka pipe a nie duże 'i' albo małe 'L'
try {
	// code
} catch (Exception1 | Exception2 $ex) {
	// handle 1 or 2
}

// strpos na minus :)
echo "Rabarbar"[-3] , "\n"; // "b", czyli trzecia od tyłu
echo strpos("Rabar[bar]","a",-5), "\n"; // szukam "a" od 5-ego znaku od końca

// od 7.4 właściwości klas mogą mieć typ!
class Order {
	public int $priority;
	public string $description;
}

// arrow functions, czyli anonimowe funkcje fn() ze strzałką => zamiast bloku
// z definicją { }
$some="thing";
$fo = fn($x) => $x . $some; // MAGIA :) Argument $x zmodyfikowany przez konkatenację z $some
echo $fo("every"), "\n"; // wszystko !

// jeżeli coś nie istnieje, to przypisz wartość, ale jak istnieje to zostaw
// operator  ??=
$doesNotExist ??= 777;
echo $doesNotExist,"\n"; // 777
$exist = 888;
$exist ??= 999; // nie stanie się 999 bo istnieje
echo $exist,"\n";

// wypakowanie elementów tablicy A przy definiowaniu elementów tablicy B
$Atab = [4,5,6];
$Btab = [1,2,3,...$Atab,7];
browse($Btab); // nice!

// dodano separator do literałów liczbowych, fajne (czytelność się podnosi)
echo 1000000, " ", 1_000_000 , "\n"; //i tu milion i tu milion




/* RÓŻNE, POZOSTAŁE, UOGÓLNENIA
 * is_real() znika, ma być is_float()
 * użycie "parent" w klasie, która nie ma przodka - zabronione
 * pojawiają się SŁABE referencja, które nie chronią obiektu przed zniszczeniem !!!
 * od 7.2 znika __autoload() !! -> spl_autoload_register()
 * od 7.2 znika each(), parse_str() traci część argumentów
 * ogólnie: od 7.0 położono duży nacisk na obsługę różnych znaków, języków lokalnych itp.
 * gdy klasa abstrakcyjna dziedziczy po innej klasie abstrakcyjnej może nadpisać jej abstrakcyjne metody
 * dla fanów kryptografii : Sodium stało się częścią core'a
 *
 * trochę nowych funkcji:
 *	https://www.php.net/manual/en/migration70.new-functions.php
 *	https://www.php.net/manual/en/migration71.new-functions.php
 *	https://www.php.net/manual/en/migration72.new-functions.php
 *	https://www.php.net/manual/en/migration73.new-functions.php
 *	https://www.php.net/manual/en/migration74.new-functions.php
 *
 * trochę zmienionych funkcji:
 *	https://www.php.net/manual/en/migration70.changed-functions.php
 *	https://www.php.net/manual/en/migration71.changed-functions.php
 *
 *
 * nowe klasy i interfejsy:
 *	https://www.php.net/manual/en/migration70.classes.php
 *
 * kilka nowych stałych:
 *	https://www.php.net/manual/en/migration70.constants.php
 *	https://www.php.net/manual/en/migration71.constants.php
 *	https://www.php.net/manual/en/migration72.constants.php
 *	https://www.php.net/manual/en/migration73.constants.php
 *	https://www.php.net/manual/en/migration74.constants.php
*/

# ZAPOMNIAŁEM O use :)
class XYZ extends \stdClass {
	static function show() {
		echo "Called class: ", get_called_class(),eol;
	}
}

}
namespace B {
	\A\XYZ::show();
	use \A\XYZ;  // mogę użyć klasy XYZ z przestrzeni \A
	XYZ::show();

}



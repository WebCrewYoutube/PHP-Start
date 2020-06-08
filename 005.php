<?php
require_once 'lib/browse.php';

############################################################################
# 005 : programowanie zorientowane obiektowo czyli słów kilka o klasach
# http://docs.php.net/manual/en/language.oop5.php

# klasa "ogólna" w PHP
$o = new StdClass();
browse($o);

$o->a=10; # własność właśnie wymyślona w locie
$o->b="b";
$o->{'c'} = [10,200,3000];


// $o jest identyfikatorem, który pozwala odnaleźć obiekt właściwy
$o2 = $o; // $o2 jest kopią identyfikatora (i prowadzi do tego samego obiektu)
$o2->a=20;
$o->b="x";

browse($o,2);
browse($o2,2);

$o3 = &$o; // $o3 jest referencją $o, w praktyce i tak prowadzi do tego samego obiektu
$o3->a=200;
echo $o->a , " " , $o2->a, " ", $o3->a, "\n";  // 200
hr();


# klasy i uprawnienia (modyfikator dostępu do składowej): public, private, protected | metody i metody statyczne
class AClass {
   public $name; // pełny dostęp zewsząd
   private $var = "I am a private variable."; // dostęp tylko wewnątrz klasy
   protected $lastname = "Kovalsky"; // dostęp wewnątrz klasy, przy dziedziczeniu
   // metoda
   public function show() {
      echo "get_class|", get_class($this), "\n", $this->var, "\n";
   }
   // metoda statyczne
   static function notLikeThis() {
      echo "Message from the static function.\n";
   }
}

$object = new AClass;
$object->show();
// $object->var; // nie można, var jest private !
$object->name = "Ryshard"; // można, name jest public
AClass::notLikeThis();
browse($object,2);
hr();


# KONSTRUKTOR i DESTRUKTOR klasy, stałe w klasie, dzidziczenie, metoda finalna
class BClass {
   public const C = "[C]onstant";
   private const D = "other [D] constant";
   static function sf() {
      echo self::C, ",", self::D," ->sf()\n"; // self:: (nie ma obiektu $this)
   }
   function __construct($prefix="") {
      echo $prefix,"BClass object is created.",
		  __CLASS__,"/",get_class($this),
		  "/",get_called_class(),"\n\n";
   }
   function __destruct(){ "do nothing"; }
   final public function finalg() { // nie da się jej nadpisać przy dziedziczeniu
      echo "Cannot override final method\n";
   }
}
BClass::sf();
echo BClass::C,"\n";
//echo BClass::D,"\n"; // error! (private)
$object = new BClass();

# Konstruktor nie jest wywoływany niejawnie w przypadku dziedziczenia, trzeba go wywołać
# parent::__construct();
class CClass extends BClass { // extends - dziedziczenie
   function __construct() {
      parent::__construct("->"); // BClass konstruktor, sam się nie wywoła
      echo "CClass object is created.\n\n";
   }
   function __destruct() {
      parent::__destruct(); # BClass destruktor, sam się nie wywoła
      print "CClass object was destroyed.\n";
   }
   // public function finalg() {} // nie można! w BClass to była metoda finalna ()
}
$x = new CClass();
echo $x::C, "\n"; #obiekt też ma dostęp do stałych klasy (stała odziedziczona z BClass)
//echo $x::D, "\n"; #error, nie dziedziczymy private
unset($x); // destruktor poleci...
hr();

##################################################################################################
# Abstrakcyjna klasa
abstract class AbtClass {
   abstract public function f1();
}

class DClass extends AbtClass { # DClass MUSI mieć f1(), albo abstract f1()
   public function f1() { # bez tej definicji f1() byłby błąd przy uruchomieniu
      echo "... day like everyday ...\n";
   }
}

# Interfejsy (zawsze public z natury interfejsu).
interface iFace {
   public function f1($var);
}
	# interfejs "dziedziczy" po innym
interface iFaceSecond extends iFace {
   public function f2();
}
	# implements implementuje interfejs
class EClass implements iFaceSecond {
   public function f1($var) { echo $var; }  # musi być f1() zgodnei z interfejsem iFace !
   public function f2() { } # musi być f2() zgodnei z interfejsem iFaceSecond !
}
	#  W PHP nie można dziedziczyć od 2 klas równocześnie, ale można dziedziczyć od klasy i interfejsów.
interface iFaceNext{
	public function f3();
}
class Example extends EClass implements IFaceSecond, iFaceNext {
	public function f3() {}
}


#########
# w PHP są klasy anonimowe
$anonClass = new class {
   static public function f1() { echo "Wow! This is static method.\n"; }
   public function f2() { echo "Wow! Just wow...\n"; }
};
$o = new $anonClass();
$o->f2(); // Wow!
$anonClass::f1();
hr();

# settery, gettery i inne magiczne metody :) (nie omawiam każdej, podaję niektóre !)
# Magiczne metody dają niesamowite możliwości elastycznego reagowania na sytuacje używania obiektu klasy w nieprzewidziany sposób, mogą podnieść poziom abstrakcji i uogólnienia, jednak trzeba je właściwie wykorzystać, aby uniknąć sytuacji, w której kod zachowuje się nieprzewidywalnie i nieintuicyjnie.
class FClass {
	private $field=0;
	// odpala się przy próbie przypisania wartości do nieistniejącego/chronionego/prywatnego pola klasy
   public function __set($name,$value) {
      echo "Set: [$name]=",$value,"\n";
      $this->$name = $value;
   }
   // odpala się przy próbie odczytania wartości nieistniejącego/chronionego/prywatnego pola klasy
   public function __get($name) {
      echo "Get: [$name]? Something went wrong\n";
      return $this->$name = "default text value"; // niejawny __set !!
   }
   // odbył się test na istnienie własności (isset(), empty()) nieistniejącej/chronionej/prywatnej
   public function __isset($name) {
      echo '$this->'.$name." isset test\n";
   }
   // usunięto cechę nieistniejącą/chronioną/prywatną
   public function __unset($name) {
      echo '[Unset]: $this->'.$name."!\n";
   }
   // gdy obiekt wyświetlam
   public function __toString() {
      return "this object is beautifully described\n";
   }
   // gdy obiekt odpalasz jak funkcję
   public function __invoke($arg) {
      echo "U invoke object as function whit arg $arg\n";
   }
   // gdy wywołujesz funkcję $this->$func($args), do której nie ma dostępu/nie istnieje
   public function __call($func,$args) {
      echo "Call object->$func (".$args[0].")\n";
   }
   // __callStatic() -> self::$func($args)
   # inne magiczne metody
   // __sleep(), __wakeup(), __serialize(), __unserialize(), __set_state(), __debugInfo()

}
$o = new FClass();
$o->x=100; // __set
$o->x=200; // nie __set bo już jest własność x
echo $o->x,"\n"; // 200
echo $o->y,"\n"; // y nie istnieje, ale nastąpiła próba jego pobrania
echo $o->z ?? "ups\n"; // niejawne isset($o->z) ? zatem odpalam __isset
unset($o->field); // __unset, bo field nie istnieje lub jest prywatny/chroniony
echo $o; // __toString()
$o(5); // __invoke()
$o->fun1(15); // _call
browse($o,2);
hr();

# klonowanie obiektu ( metodą __clone() można zdefiniować własne klonowanie )
# $clone = clone $orginal; # płytka kopia atrybutów, referencje zostaną referencjami
class A {
   static public $nr=0;
   public $id;
   public $ref;
   public function __construct() {
      echo __CLASS__." number ".self::$nr." created\n";
      $this->id = self::$nr++;
   }
}
$a = new A; //0
$b = new A; //1
$b->ref = $a; // $b->ref jest referencją nie kopią
$c = clone $b; // płytka kopia wszystkich właściwości, referencje zostaną referencjami, $c jest nowym obiektem o nowym identyfikatorze, ale np. nie uruchamiał się konstruktor...
browse($a,2);
browse($b,2);
browse($c,2);
$c->id=10; # zmieni siętylko id obiektu $c, to jego własne id
$c->ref->id=100; // a tu chamstwo! Zmieniam $a->id :)
echo $b->id,"\n"; // zachował swoje 1, mimo, że $c->id = 10 nie zmieniło to $b->id
echo $a->id,"\n"; // atrybut ref jest referencją, dlatego zmieniła się wartość $a, bo $a, $b->ref, oraz $c->ref wskazują na to samo.

# Ciekawostka i najlepszy QUIZ, tzw QUIZ bezobjawowy, czyli bez nagrody ale za to i bez kary.
# Zwróćcie uwagę na numery obiektów, dlaczego dostają takie identyfikatory a nie inne?
# (np. object(StdClass)#1, object(A)#4 itp.)
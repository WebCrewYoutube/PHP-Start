<?php
require_once 'lib/browse.php';

############################################################################
# 005 : klasy
# http://docs.php.net/manual/en/language.oop5.php

# klasa "ogólna" w PHP
$o = new StdClass();
$o->a=10; # własność właśnie wymyślona
$o->b="b";
$o->{'c'} = [1,2,3];

// $o jest identyfikatorem, który pozwala odnaleźć obiekt właściwy
$o2 = $o; // $o2 jest kopią identyfikatora (i prowadzi do tego samego obiektu)
$o2->a=20;
$o->b="x";

browse($o,2);
browse($o2,2);

$o3 = &$o; // $o3 jest referencją $o, w praktyce i tak prowadzi do tego samego obiektu
$o3->a=200;
browse($o); // 200
browse($o2);  // 200

# klasy
class AClass {
   public $name; // pełny dostęp zewsząd
   private $var = "default"; // dostęp tylko wewnątrz klasy
   protected $lastname = "Kovalsky"; // dostęp wewnątrz klasy, przy dziedziczeniu

   public function show() {
      echo "get_class|", get_class($this), "\n",
         $this->var, "\n";

   }

   static function notLikeThis() {
      echo "static\n";
   }
}

$object = new AClass;

$object->show();

// $object->$var; // nie można
$object->name = "Ryshard";

echo AClass::notLikeThis();

browse($object,2);
###########################################
hr();
class BClass {
   public const C = "constant";
   private const D = "other constant";
   static function f() {
      echo self::C,"\n"; // self:: (nie ma obiektu $this)
   }
   function __construct($prefix="") {
      echo $prefix,"BClass object is created.",__CLASS__,"/",get_class($this),"/",get_called_class(),"\n";
   }
   final public function g() { //
      echo "Cannot override final method\n";
   }
}
BClass::f();
echo BClass::C,"\n";
$object = new BClass("");

# Konstruktor nie jest wywoływany niejawnie w przypadku dziedziczenia, trzeba go wywołać
# parent::__construct();
class CClass extends BClass { // extends - dziedziczenie
   function __construct() {
      parent::__construct("->"); // BClass konstruktor
      echo "CClass object is created.\n\n";
   }
   function __destruct() {
      # jeżeli klasa przodka ma destruktor, trzeba go tutaj jawnie przywołać:
      # parent::__destruct();
      print "CClass object was destroyed.\n";
   }
   // public function g() {} // nie można! w BClass to była metoda finalna ()
}
$x = new CClass();
unset($x);

$x = new CClass();
echo $x::C, "\n"; #obiekt też ma dostęp do stałych klasy (stała odziedziczona z BClass)
hr();


# Abstrakcyjna klasa
abstract class AbtClass {
   abstract public function f1();
}

class DClass extends AbtClass { # DClass MUSI mieć f1(), albo abstract f1()
   public function f1() { # bez tej definicji f1() byłby błąd przy uruchomieniu
      echo "... day like everyday ...\n";
   }
}

# Interfejsy (zawsze public z natury interfejsu)
interface iFace {
   public function f1($var);
}
interface iFaceSecond extends iFace { # interfejs "dziedziczy" po innym
   public function f2();
}

class EClass implements iFaceSecond { # implements implementuje interfejs
   public function f1($var) { # musi być f1() zgodnei z interfejsem iFace !
      echo $var;
   }
   public function f2() { # musi być f2() zgodnei z interfejsem iFaceSecond !
   }
}

# w PHP są klasy anonimowe
$anonClass = new class {
   static public function f1() { echo "Wow!\n"; }
};
$o = new $anonClass();
$o->f1(); // Wow!

# settery, gettery i inne magiczne metody :)
class FClass {
   public function __set($name,$value) { // odpala się automatycznie przy próbie przypisania wartości do pola klasy
      echo "Set: [$name]=",$value,"\n";
      $this->$name = $value;
   }
   public function __get($name) { // odpala się automatycznie przy próbie odczytania wartości pola/atrybutu klasy
      echo "Get: [$name]\n";
      return $this->$name;
   }
   public function __isset($name) {  // odbył się test na istnienie pola/atrybutu
      echo '$this->'.$name." isset test\n";
   }
   public function __unset($name) { // usunięto pole/atrybut (taki destruktor dla własności obiektu, a nie dla całego obiektu)
      echo '$this->'.$name." unset field\n";
   }
   public function __toString() {
      return "this object is beautifully described\n";
   }
   public function __invoke($arg) { // gdy obiekt odpalasz jak funkcję
      echo "U invoke object as function whit arg $arg\n";
   }
   public function __call($func,$args) { // gdy wywołujesz funkcję $this->$func($args)
      echo "Call object->$func (".$args[0].")\n";
   } // __call_static() - wywołanie statyczne dla klasy nie obiktu
   // inne __sleep(), __wakeup(), __serialize(), __unserialize(), __set_state(), __debugInfo()

}
$o = new FClass();
$o->x=100;
echo $o->x,"\n"; // 100

echo $o->b ?? "ups\n"; // niejawne isset($o->b) ?
unset($o->c);
echo $o;
$o(5);
$o->fun1(15);
hr();




# klonowanie obiektu ( metodą __clone() można zdefiniować własne klonowanie )
# $clone = clone $orginal; # płytka kopia atrybutów, referencje zostaną referencjami
class A {
   static public $nr=0;
   public $id;
   public $ref;
   public function __construct() {
      echo "`A` number ".self::$nr." created\n";
      $this->id = self::$nr++;
   }
}
$a = new A; //0
$b = new A; //1
$b->ref = $a; // $b->ref jest referencją nie kopią
$c = clone $b; // płytka kopia wszystkich właściwości, referencje zostaną referencjami
browse($a,2);
browse($b,2);
browse($c,2);
$c->id=10;
$c->ref->id=100; // zmieniam $a->id :)
echo $b->id,"\n"; // zachował swoje 1, mimo, że $c->id = 10 nie zmieniło to $b->id
echo $a->id,"\n"; // atrybut ref jest referencją, dlatego zmieniła się wartość $a, bo $a, $b->ref, oraz $c->ref wskazują na to samo.

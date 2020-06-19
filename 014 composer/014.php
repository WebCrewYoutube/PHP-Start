<?php
$loader = require __DIR__ . '/vendor/autoload.php';
use webcrew\{Test\AClass as A, BClass as B};
$a = new A;
$b = new B;
e(1,2,3);
hr('#');
browse($loader,2);



?>
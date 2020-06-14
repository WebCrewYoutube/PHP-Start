<?php
require_once './lib/browse.php';

############################################################################
# 010 : Współpraca z bazą danych (na przykładzie MySQL)
# Wymagania:
#	znajomość relacyjnych baz danych
#	SQL
#	MySQL lub podobne
# Korzystam z:
#	WAMP https://www.wampserver.com/en/
#	czyli: Windows+Apache+MySQL+PHP (PHP 7.4, Apache 2.4.41, MySQL 8)

// model do pracy z bazą danych MySQL (nieuniwersalne podejście, silnie powiązane z konkretną bazą, tutaj MySQL)
$db = new mysqli('localhost:3308','root','1','testdb');
	$result = new mysqli_result($db); // niekonieczne, ale mam podpowiedzi
$result = $db->query("SELECT * FROM table_a");
browse($result,2);
while ($row = $result->fetch_assoc()) {
	browse($row,0,"\n");
}
$result->free(); // zwalnia pamięć przechowującą $result
	# e (moja wersja echo, które łamie linię po LENLINE=47)
e("Ostatnie zapytanie wychwyciło " , $db->affected_rows,
	" wiersze z tabeli.\n");
$db->query("Co to za absurdalne zapytanie SQL ?");
if ($db->errno) e($db->error, "\n");
$db->close();

// PDO (PHP Data Objects) to model ogólny pracy z danymi.
// Określa się z jakim źródłem danych będziemy pracować, np. z bazami SQL: mysql czy sqlite itd. PDO dostarcza takiego poziomu abstrakcji, że zadziała bez względu na rodzaj użytej bazy danych.
// Wspierane obecnie bazy (wg manual'a): curbid, MS SQL Server, firebird, ibm db2, mysql,oci, odbc v3, postgreSQL, SQLite, sql Azure, 4D, freeTDS
# https://www.php.net/manual/en/class.pdo.php
# https://www.php.net/manual/en/class.pdostatement.php

$db = new PDO('mysql:host=localhost:3308;dbname=testdb', 'root', '1');

// exec: wykona zapytanie SQL i zwróci ilość "zainfekowanych" wierszy
$res = $db->exec("INSERT INTO table_a SET
	description='Banan to wierny towarzysz.\n',
	value='75.19'");
echo "Add: ",$res," row\n";
echo "id:=",$db->lastInsertId(),"\n"; // wartość klucza wstawionego rekordu

// query: pytanie z wynikiem
$res = $db->query("SELECT * FROM table_a",PDO::FETCH_ASSOC);
browse($res,2);
foreach ($res as $row) {
	browse($row,0,"\n");
}

echo "Del: ",
	$db->exec("DELETE FROM table_a WHERE value=75.19"), " row\n";
hr();

// przygotowanie i pobieranie
$prep = $db->prepare("SELECT * FROM table_a");
/// Jakiś kod ...
browse($prep,2);
$prep->execute();
	browse($prep->errorCode(),2); // 00000 - brak błędu
while($res = $prep->fetch(PDO::FETCH_OBJ))
	e($res->description," [", $res->id, "]\n");

// błędy
$prep = $db->prepare("SELECT * FROM table_b");
$prep->execute();
browse($prep->errorCode(),2);
browse($prep->errorInfo(),2);


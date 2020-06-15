
<?php
# Zanim zacznę, mała opowieść: Aplikacja web'owa ma pewien szkielet działania.
# Oto na hoście A (host, najprościej to komputer w sieci) znajduje się usługa stron internetowych, serwer WWW (np. Apache), która to usługa stoi sobie na porcie 80 i nasłuchuje, czy ktoś aby nie wszedł na serwer z rządaniem pobrania strony internetowej. Załóżmy, że ten serwer ma adres 'http://lubie.kapcie.pl. Na drugim hoście B na drugim końcu świata, jakiś klient wyposażony w przeglądarkę internetową (np. Chrome albo Firefox) korzystając z protokołu http/https i posiadając URL serwera (hosta A) 'wszedł' na tę stronę, podając w pasku adresu 'lubie.kapcie.pl'.
# I teraz dzieje się magia. W dość uproszczony sposób wygląda to tak:
# Przeglądarka klienta wysyła na serwer prośbę (a jak jest agrewsywna to żądanie) aby ten pokazał jej stronę. Żądanie trafia na serwer przez port 80 (dla protokołu http, ale może być inny). I serwer - mówiąc w skrócie - odsyła przez internet odpowiednie pliki do klienta. Te pliki muszą mieć też jakiś zrozumiały przez przeglądarkę format, zawierać dające się zinterpretować dane. I mają! Są to pliki zawierające
# np. HTML+CSS+JS.
# A jak powstają te pliki? Mogą być ręcznie (statycznie) utworzone, takie 'sztywne' i po prostu są przesyłane. Najczęście w szkole robicie takie projekty :)
# Ale mogą też być generowane w locie za pomocą bardziej zaawansowanego narzędzia.
# To co zostaje wysłane to dalej HTML+CSS+JS ale jest tworzone np. przez PHP (albo część tego).
# Modeli tworzenia stron jest wiele. Ale może tera zajmijmy się prostym zobrazowaniem tej "automatycznej generacji kodu". Czyli co się dzieje na serwerze ?
#
# P.S. Protokół można bardzo prosto wytłumaczyć jako zbiór wspólnych zasad komunikacji, wymiany danych itp. Aby dwa hosty wymieniały prawidłowo dane muszą oba znać protokół tę wymianę określający. Bardziej szczegółowo:
# https://en.wikipedia.org/wiki/Communication_protocol
#


############################################################################
# 011 : PHP a HTML, czyli na co komu PHP ?
# Od takiego pytania mogłem zacząć ten kurs, ale colowo go unikałem. Chciałem, abyście skupili się na samym języku: instrukcje sterujące, zmienne, funkcje, klasy...
# I teraz nadszedł czas, prawdopodobnie najwyższy czas, aby pokazać PHP w jego środowisku, czyli tam, gdzie PeHaPy pływają radośnie i czują się szczęśliwe.
?>
<?php
header("Content-type: text/html; charset=utf-8");
header("Refresh: 3;")
?><!doctype html>
<html>
<head>
   <meta charset="utf-8">
   <title>HTML5+PHP+CSS</title>
   <script src="jquery-3.5.1.min.js"></script>
</head>
<body>
   <div style="font-size: 30px;" align="center">
		<?php echo date("Y-m-d") ?>
		Szczypanko w boczek:
		<br>
		<img src="meme.jpg" style="height: 300px;"><hr>
		<div>Tomasz Jaśniewski webjasiek@WebCrew</div>
	</div>
	<p style="font-size:20px; color: #f00;
	   text-indent: 100px; text-align: justify;">
		Powszechnie wiadomo, że szczypanie owcy (szczególnie w lewy boczek) pozytywnie nastraja ją do wzrostu futra. Dlatego chętnie szczypiemy, tym bardziej, że ta czynność podnosi sprawność szczypiącego w posługiwaniu się klawiaturą i zwiększa kwalifikacje w posługiwaniu się językiem obcym podczas gestykulacji słów, których nie znamy.<br>
		(udowodnione na http://fakenews.only-true.org)
	<!-- formularz -->
	<form method="POST" action="011.php"
		  style="display: inline-block;">
		<button name="send" value="I php You!" id="but">OK</button>
		<?php echo ($_POST["send"]) ?? "......................."; ?>
	</form>

	</p>
	<table border="1" width="90%">
		<tr>
			<th>Id</th>
			<th>Czas utworzenia</th>
			<th>Wartość</th>
		</tr>
<?php
		$db = new PDO(
			'mysql:host=localhost:3308;dbname=testdb', 'root', '1');
		$res = $db->query("SELECT * FROM table_a",PDO::FETCH_ASSOC);
		# wiersze tabeli zapełnione danymi z bazy danych
		foreach ($res as $row) {
			echo<<<END
				<tr>
					<td align="center">{$row["id"]}</td>
					<td align="right">{$row["ctime"]}</td>
					<td align="right">{$row["value"]}</td>
				</tr>
				END;
		}
?>
	</table>
</body>
</html>
<?php
/*
 * Nie przywiązujcie się do tego, co tu pokazałem. W praktyce bowiem PHP nie tworzy wprost treści HTML, co raczej dostarcza odpowiednie dane (np. w postaci JSON). Co więc tworzy piękną stronę? Tworzy go np.: Javascript już po stronie klienta.
 * Mówiąc krótko - nastąpiła pewna specjalizacja w świecie aplikacji webowych.
 * Programista mógłby za pomocą PHP wygenerować cały plik z całą zawartością wysyłaną od razu przez serwer. Ale specjalizacja ma to do siebie, że skupiamy się na wąskim zakresie działań ale za to doprowadzamy je do mistrzostwa. W efekcie powstają różne modele tworzenia kodu oparte o tę zasadę specjalizacji. Jednym z nich jest MVC.
 * Projekty aplikacji są jakby rozbite: czynności na serwerze, które otrzymują polecenia i operują na informacji, obrabiają ją, zapisują, czytają, selekcjonują i ogólnie kontrolują logiką aplikacji to [C]ontroller. [V]iew to właśnie ta część aplikacji,która odpowiada za wizualizację (GUI).
 * https://pl.wikipedia.org/wiki/Model-View-Controller
 * I tak się składa, że nasz PHP siedzi sobie na serwerze i robi za [C] w całym modelu MVC :)
 * Co więcej. Istnieją gotowe mechaniki, tzw. FRAMEWORK'i
 * https://pl.wikipedia.org/wiki/Framework
 * dostarczające cały szkielet do budowania aplikacji, oparte właśnie na MVC chociaż istnieją inne wzorce projektowe. Framework'i wspierają tworzenie aplikacji, dostarczają gotowe rozwiązania, szczególnie takie, które są wspólne i powtarzalne dla wielu aplikacji webowych, np. mogą silnie wspierać pracę z bazą danych, dostarczając nam znacznie bardziej intuicyjnych i zautomatyzowanych funkcji pracy z bazami danych.
 * Wkrótce o tym się przekonacie ;)
 */
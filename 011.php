
<?php
############################################################################
# 011 : PHP a HTML, czyli na co komu PHP ?
# Od takiego pytania mog³em zacz¹æ ten kurs, ale colowo go unika³em. Chcia³em, abyœcie skupili siê na samym jêzyku: instrukcje steruj¹ce, zmienne, funkcje, klasy...
# I teraz nadszed³ czas, prawdopodobnie najwy¿szy czas, aby pokazaæ PHP w jego œrodowisku, czyli tam, gdzie PeHaPy p³ywaj¹ radoœnie i czuj¹ siê szczêœliwe.
?>
<?php
header("Content-type: text/html; charset=ansi");
header("Refresh: 3;")
?><!doctype html>
<html>
<head>
	<meta charset="ansi">
	<title>HTML5+PHP+CSS</title>
	<script src="jquery-3.5.1.min.js"></script>
</head>
<body>
	<div style="font-size: 30px;" align="center">
		<?php echo date("Y-m-d") ?>
		Szczypanko w boczek:
		<br>
		<img src="meme.jpg" style="height: 300px;"><hr>
		<div>Tomasz Jaœniewski webjasiek@WebCrew</div>
	</div>
	<p style="font-size:20px; color: #f00;
	   text-indent: 100px; text-align: justify;">
		Powszechnie wiadomo, ¿e szczypanie owcy (szczególnie w lewy boczek) pozytywnie nastraja j¹ do wzrostu futra. Dlatego chêtnie szczypiemy, tym bardziej, ¿e ta czynnoœæ podnosi sprawnoœæ szczypi¹cego w pos³ugiwaniu siê klawiatur¹ i zwiêksza kwalifikacje w pos³ugiwaniu siê jêzykiem obcym podczas gestykulacji s³ów, których nie znamy.<br>
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
			<th>Wartoœæ</th>
		</tr>
	<?php
		$db = new PDO(
			'mysql:host=localhost:3308;dbname=testdb', 'root', '1');
		$res = $db->query("SELECT * FROM table_a",PDO::FETCH_ASSOC);
		# wiersze tabeli zape³nione danymi z bazy danych
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

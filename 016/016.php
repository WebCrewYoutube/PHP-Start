<?php
require_once __DIR__.'/vendor/autoload.php';

############################################################################
# 016 # Przykład wykorzystania "cudzej" biblioteki;
# 1) przygotuj sobie plik composer.json
# 2) php composer.phar i
# 3) Po instalacji czas na naukę: trzeba poznać bibliotekę dostarczoną
# przez composer'a abyśmy mogli z niej korzystać.
# 4) Gdy już umiemy korzystać... korzystamy!
# 5) sponsorem odcinka jest słowo ... `korzystać`!
############################################################################

use Dompdf\Dompdf;
use Dompdf\Options;

$opt = new Options();
$opt->set('defaultFont','DejaVu Sans');

$pdf = new Dompdf($opt);

# image jako kod base64 + integracja z kodem html
$img = "lubiejajka.jpg";
$type = pathinfo($img, PATHINFO_EXTENSION);
$data = file_get_contents($img);
$img_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$html=<<<HTML
<html>
<head>
	<meta charset='UTF-8'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<style>
		body { font-family: DejaVu Sans; }
		p { color: #ff0000; font-size: 2.5cm; }
		div	{ font-size: 1cm; text-decoration: underline; }
	</style>
	</head>
	<body>
		<p>Narzędzia kucharskie vs kura</p>
		<table border=1>
			<tr>
				<td>Kura</td><td>kurze</td><td>wilkiem!</td>
			</tr>
		</table>
		<hr>
		<img src="$img_base64" width="300" style="float: left;">
		<div> Potrawy z jajek są wyśmienite! Niektórzy jednak twierdzą, że wykorzystujemy kury i bezczelnie je okradamy.</div>
	</body>
</html>
HTML;
//$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

$pdf->loadHtml($html,"utf-8");
$pdf->setPaper('A4','landscape');
$pdf->render();

if (false) { // do pliku
	ob_start();
	$pdf->stream();
	$pdffile = ob_get_contents();
	file_put_contents('./plik.pdf',$pdffile);
	ob_end_clean();
} else { // na standardowe wyjście
	$pdf->stream();
}

?>
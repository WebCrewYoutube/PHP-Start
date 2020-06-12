<?php
require_once('lib/browse.php');
############################################################################
# 008: Podstawowa obsługa plików i katalogów
# http://docs.php.net/manual/en/book.dir.php
# http://docs.php.net/manual/en/book.filesystem.php
# http://docs.php.net/manual/en/book.fileinfo.php
# http://docs.php.net/manual/en/book.dio.php

# Stałe
echo DIRECTORY_SEPARATOR, " ", PATH_SEPARATOR, "\n";
echo getcwd(); // aktualny katalog ;
// chdir("c:/"); // nowy katalog (zmień to, co daje getcwd)
hr('.');

# 1) Przeglądnij zawartość katalogu
$list = scandir("./",SCANDIR_SORT_ASCENDING); // SCANDIR_SORT_DESCENDING
browse($list,0,"\n");

// class : Directory
$dir = dir("./"); // a tak nie da rady!    $dir = new Directory("./");
browse($dir,2);
while ($name = $dir->read()) echo "$name ";
hr();

// type: resource (stream)
$resource = opendir("./");
browse($resource,2);
while (false!== ($name = readdir($resource)) ) echo "$name ";
hr();

// Aby ponowić przegląd katalogu, muszę "przewinąć" do początku.
// Uwaga, wszystkie poniższe zapisy robią to samo!
$dir->rewind();
rewinddir($resource);
rewinddir($dir->handle);

// kończ pracę z katalogiem
closedir($resource);

# 2) Informacje o pliku z użyciem: class finfo
if (!($finf = new finfo(FILEINFO_NONE))) die("Nieudana operacja");

echo $finf->file("./"), "\n"; // directory
echo $finf->file("./001.php");
hr();

$finf->set_flags(FILEINFO_MIME_TYPE);
echo $finf->file("./001.php");
hr();

$finf->set_flags(FILEINFO_MIME_ENCODING);
echo $finf->file("./001.php");
hr();

echo mime_content_type("./005.php");
hr();

// Można też podać własne typy mime, o ile umiemy taki plik zrobić. Ale domyślny prawdopodobnie wystarczy:
// $finf = new finfo(FILEINFO_MIME,"/path/to/magic");

// Można też utworzyć tak;
$fires = finfo_open(FILEINFO_MIME_ENCODING);
echo finfo_file($fires,"005.php");
hr();
finfo_close($fires);

# 3) operacje na plikach : WIELE przydatnych funkcji, omówię kilka
# http://docs.php.net/manual/en/filesystem.constants.php
# http://docs.php.net/manual/en/book.filesystem.php

echo basename("./lib/browse.php"), PHP_EOL;//ze ścieżki wyciąga plik
echo basename("./lib/browse.php",".php"),"\n";//ze ścieżki wyciąga plik bez
echo dirname("./lib/browse.php"), "\n"; // ze ścieżki wyciąga katalog
hr();

if (copy("./example.txt","./example2.txt")) echo "Copy...\n";
// Przy okazji:
// @ przed funkcjami zablokuje ostrzeżenia itp. gdyby coś poszło nie tak
@copy("./example5.txt","./example2.txt");

unlink("./example2.txt"); // i nie ma pliku

if (!file_exists("./12313")) echo "Brak pliku 12313\n";

hr();
$file_string = file_get_contents("example.csv");
echo $file_string; // cały plik do napisu
hr();
file_put_contents("example.csv", $file_string); // włóż napis do pliku

$array = file("example.txt"); // linie pliku do tablicy (z \n)
browse($array,1," ");

echo date("Y-m-d H:i:s",(filectime("example.txt"))), "\n"; // create
echo date("Y-m-d H:i:s",(fileatime("example.txt"))), "\n"; // access
echo date("Y-m-d H:i:s",(filemtime("example.txt"))), "\n"; // modify
echo filesize("example.txt"), "\n"; // byte
echo filetype("example.csv"), "\n"; // file | dir

hr();

$fh = fopen("example.csv","r"); // Otwórz plik, $fh - uchwyt do pliku
$save = fopen("new_example.txt","w");
while ($line = fgetcsv($fh, 0, ';')) {
	foreach($line as $v) {
		echo $v,"\t";
		fwrite($save, $v."\n");
	}
	echo "\n";
}

fclose($fh); // zamknij plik, zakończ pracę z plikiem
fclose($save);

// mkdir, rmdir - tworzy i kasuje katalogi
// stat - tablica z informacjami o pliku $array = stat("example.txt");
// rewind - wraca na początek pliku
// rename
// parse_ini_file - z pliku .ini robi tablicę!
// fseek - ustawia wskaźnik odczytu na danym miejscu w pliku


echo disk_total_space("./"),"/",disk_free_space("./"), "\n";















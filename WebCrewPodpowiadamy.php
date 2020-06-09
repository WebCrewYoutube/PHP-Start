<?php
# WYSYŁANIE PLIKÓW (formularz w HTML musi być właściwy)
# przykładowo:
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// test na obecność pliku
if (file_exists($target_file)) {
    // kod gdy plik już jest
}
// test wielkości pliku
if ($_FILES["file"]["size"] > 10000000) {
    // kod gdy plik za duży
}

// czy aby to niedozowolony plik (rozszerzenie)
if(!in_array($imageFileType,array("jpg","mp4","doc","pdf","xls"))) {
	// kod
}

// wszystko ok, upload
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // basename( $_FILES["file"]["name"]); // nazwa przekazanego pliku
} else {
 // error
}


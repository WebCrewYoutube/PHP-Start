<?php
// namespace /
// plik w ./
class ExampleClass {
	private $baseDir = 'C:\Users\Tomek\Documents\_YT\_projekty\008 PHP7 Start\github';
   public $var="value";
   function __construct(){
      echo "Eureka! ".__CLASS__." object is created.\nFile=".
		  str_replace($this->baseDir,'.',__FILE__)."\n";
   }
}
?>
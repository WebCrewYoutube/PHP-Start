<?php
/** drow `line` ;) */
function hr() {    echo "\n".str_repeat("_",55) . "\n";  }  # element estetyczny
/**
 * browse($var,$method) is my version of displaying everything (almots)
 * @param mixed var = variable for analysis and display
 * @param int method = 0|1|2 functions: print_r(0) or var_export(1) or var_dump(2), default 1
 * @author Tomasz Jaśniewski <biuro@webjasiek.pl>
*/
function browse($var,$method=1) {
   hr();
   if ($method==2) {
      var_dump($var);
      return;
   }
   $m = ($method==1) ? "var_export" : "print_r";
   if (is_array($var) and count($var) and $method!=2) {
      while (true) {
         $e = current($var);
         if ($method==0)
            echo str_replace("\n"," ", key($var)."=>[".$m($e,true)."]");
         else
            echo str_replace("\n"," ", $m($e,true));
         if (next($var)!==FALSE) echo ", ";
         else {reset($var); break; }
      }
      echo "\n" . str_repeat("-",55);
   }
   else
      echo preg_replace("/[\n\r\f]+/"," ", $m($var,true)) . "\n" . str_repeat("-",55);
   echo "\n";
}
?>
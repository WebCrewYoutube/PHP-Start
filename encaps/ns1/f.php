<?php
namespace Ns1 { # moja kapsułka, przestrzeń nazw
   function x() {
      echo "x() z Ns1 (".
			preg_replace("/.+([\w]+\.php)/","$1",__FILE__).
			")\n";
   }
   define ('Ns1\X','1');
   $varInside = 100; // nie będę mógł użyć /Ns1/$varInside (error), zmienne są zawsze w przestrzeni globalnej a nie w podprzestrzeniach;
}
/* Uwaga!
 * Zamiast
 *	namespace Ns1 { ... }
 * można na początku pliku umieścić
 *	namespace Ns1;
 */
?>
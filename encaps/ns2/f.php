<?php
namespace Ns2 {
	function x() {
		echo "x() z Ns2 (".
			preg_replace("/.+([\w]+\.php)/","$1",__FILE__).
			")\n";
	}
	define ('Ns2\X','2');
	class EmptyClass{}
}
namespace Ns3{
	function x() {
		echo "x() z Ns3\n";
		echo \Ns1\X, " - X z Ns1 wewnątrz Ns3\n";
		// echo Ns1\X, " - X z Ns1 wewnątrz Ns3\n"; // błąd! Musi być \Ns1

	}
}
?>
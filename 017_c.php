<?php
require_once './lib/browse.php';
############################################################################
# 017 : Programowanie gniazd jako przykład wykorzystania sieci.
# https://www.php.net/manual/en/ref.sockets.php
# CLIENT
e("Client",eol);
$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!socket_bind($s,'127.0.0.1',9003)) die('bind problem');
if (!socket_connect($s,'127.0.0.1',9000)) die('connect problem');

socket_getpeername($s,$addr,$port);
e("Address $addr, port $port\n");

while (true) {
	$i??=1;
	if(!socket_write($s, "step $i")) { e("write problem"); break; }
	else e("sending: 'step $i'",eol);
	usleep(500000); // 0.5 sec
	$i++;
}

e(eol,"Client off");
socket_close($s);
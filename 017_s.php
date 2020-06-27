<?php
require_once './lib/browse.php';
############################################################################
# 017 : Programowanie gniazd jako przykład wykorzystania sieci.
# https://www.php.net/manual/en/ref.sockets.php
# SERVER
e("Server",eol);
$s = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
if (!socket_bind($s,'127.0.0.1',9000)) die('bind problem');
if(!socket_listen($s)) die('listen problem');

socket_set_nonblock($s);

$clients=[];
while(true)
{
    if(($newClient = socket_accept($s)) !== false) {
        e( "Client $newClient has connected.\n" );
        $clients[] = $newClient;
		socket_getpeername($newClient,$addr,$port);
		e("Address $addr, port $port\n");
    }
	foreach ($clients as $nr => &$c) {
		$getstr = @socket_read($c, 1024);
		if($getstr!="" && explode(' ', $getstr)[1]%10==1) {
			e("from $c : ",$getstr," => ",count($clients)," clients",eol);
		}
		if (socket_last_error($c)==10054) {
			e(socket_last_error($c),":",
				socket_strerror(socket_last_error($c)),eol);
			socket_clear_error($c);
			socket_shutdown($c,2);
			unset($clients[$nr]);
			if(!count($clients)) break 2;
		}
	}
}


e("Server off");
socket_close($s);
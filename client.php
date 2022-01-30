<?php
// set ip and port
$host = getHostByName(getHostName());
$port = 10000;

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket: " . socket_strerror(socket_last_error()).PHP_EOL);

// connect to server
//$result = socket_connect($socket, $host, $port) or die("Could not connect to server: " . socket_strerror(socket_last_error()).PHP_EOL);  

echo 'Trying to connect to '.$host. ' on port '. $port . '...' . PHP_EOL;

while (true) {
	
	try {
		$result = true;
		@socket_connect($socket, $host, $port); 
	} catch(Exception $e) {
		$result = false;
		sleep(3);
	}

}

echo 'Connected'.PHP_EOL;

while(true) {
    if (PHP_OS == 'WINNT') {
        echo 'Send: ';
        $send = stream_get_line(STDIN, 1024, PHP_EOL);
    } else {
        $send = readline();
    }
    
    if($send == '/quit') {
        socket_shutdown($socket);
        socket_close($socket);
        exit;
    }
  
    socket_write($socket, $send, strlen($send)) or die("Could not send data to server\n");
}

socket_close($socket);
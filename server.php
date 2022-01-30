<?php
// set ip and port
$host = getHostByName(getHostName());
$port = 10000;
// don't timeout!
set_time_limit(0);
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket: " . socket_strerror(socket_last_error()).PHP_EOL);


if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)) {
    echo 'Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
}

// bind socket to port
$bind = socket_bind($socket, $host, $port);
// start listening for connections
$listen = socket_listen($socket);

// accept incoming connections
// spawn another socket to handle communication
$spawn = socket_accept($socket) or die("Could not accept incoming connection\n");

echo 'Em escuta'.PHP_EOL;


while($spawn){
    
    $input = socket_read($spawn, 1024);
    
    if($input) {
        
        $input = trim($input);
        
        if($input == '/quit'){
            socket_close($socket);
            socket_close($spawn);
            exit;
        }

        echo "Client Message : ".$input."\n";
    } else {
        die('Disconnected'.PHP_EOL);
    }
}
// clean up input string

socket_close($socket);
socket_close($spawn);
exit;
// reverse client input and send back

#!/usr/local/bin/php -q
<?php
error_reporting(E_ALL);

/* Permitir al script esperar para conexiones. */
set_time_limit(0);

/* Activar el volcado de salida implícito, así veremos lo que estamos obteniendo
 * mientras llega. */
ob_implicit_flush();

$address = '127.0.0.1';
$port = 8080
;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }
    /* Enviar instrucciones. */

    $msg = "\nBienvenido al chat De Prueba de PHP. \n" .
        "Para salir, escriba 'quit'. Para cerrar el servidor escriba 'shutdown'.\n";
    socket_write($msgsock, $msg, strlen($msg));

    do {
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() falló: razón: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            //$ms = "Servidor: Hola \n"; 
            //socket_write($msgsock, $ms, strlen($ms));
            continue;
        }
        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($msgsock);
            break 2;
        }
        //$talkback = "PHP:  ";
        //socket_write($msgsock, $talkback, strlen($talkback));
        echo "cliente:  $buf\n";
        $talk = readline();
        socket_write($msgsock, $talk."\n", strlen($talk."\n"));

        
    } while (true);
        
    //socket_close($msgsock);
} while (true);

socket_close($sock);
?>

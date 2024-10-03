<?php
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'blog';
    $port = 3307;

    $conn = mysqli_connect($hostname, $username, $password, $database, $port);

    if(mysqli_connect_errno()){
        printf("Erro Conexão: %s". mysqli_connect_errno());
        exit();
    }

?>
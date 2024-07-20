<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "cad_livros";

$conn = new mysqli($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Conexão falhou. Erro: " . mysqli_connect_error());
    }
?>
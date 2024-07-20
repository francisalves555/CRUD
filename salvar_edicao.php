<?php
require("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $livro_id = $_POST['id'];
    $nome = $_POST['nome'];
    $autor = $_POST['autor'];
    $numero_p = $_POST['numero_p'];
    $numero_pl = $_POST['numero_pl'];
    $status = $_POST['status'];

    $sql = "UPDATE livros_cadastrados SET nome='$nome', autor='$autor', numero_p='$numero_p', numero_pl='$numero_pl', status='$status' WHERE id=$livro_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?mensagem=editar");
        exit();
    } else {
        header("Location: index.php?mensagem=erro". $conn->error);
        exit();
    }
}
?>
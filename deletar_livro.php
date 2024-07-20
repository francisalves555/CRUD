<?php
require("conecta.php");

if (isset($_GET['id'])) {
    $livro_id = $_GET['id'];

    $sql = "DELETE FROM livros_cadastrados WHERE id=$livro_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?mensagem=deletar");
        exit();
    } else {
        header("Location: index.php?mensagem=erro". $conn->error);
        exit();}

    }
?>

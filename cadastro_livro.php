<?php
    require("conecta.php");

    $nome = $_POST['nome'];
    $autor =  $_POST['autor'];
    $numero_p = $_POST['numero_p'];
    $numero_pl = empty($_POST['numero_pl']) ? 0 : $_POST['numero_pl'];
    $status = $_POST['status'];

    if (isset($_FILES['foto'])) {
        $foto = $_FILES['foto'];
        $foto_nome = $foto['name']; 
        $foto_tmp = $foto['tmp_name'];

        $diretorio_destino = "fotos/";

        $foto_destino = $diretorio_destino . uniqid('img_', true) . '_' . $foto_nome;

        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            $foto = $foto_destino;

            $sql = "INSERT INTO livros_cadastrados (nome, autor, numero_p, numero_pl, status, foto)
                    VALUES ('$nome', '$autor', '$numero_p', '$numero_pl', '$status', '$foto')";

            if ($conn->query($sql) === TRUE) {
                header("Location: index.php?mensagem=sucesso");
                exit();
            } else {
                header("Location: index.php?mensagem=erro". $conn->error);
                exit();
            }
        }
    }
?>
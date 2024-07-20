<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de livros para ler</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <!-- CADASTRO DE LIVROS -->
    <div class="container">
        
           <!-- MENSAGEM DE SUCESSO/ERRO -->
        <?php
            if (isset($_GET['mensagem'])) {
                if ($_GET['mensagem'] == 'sucesso') {
                    echo '<div class="alert alert-success" role="alert">Livro cadastrado com sucesso!</div>';
                } else if ($_GET['mensagem'] == 'erro') {
                    echo '<div class="alert alert-danger" role="alert">Ocorreu um erro ao inserir o registro.</div>';
                }
                else if ($_GET['mensagem'] == 'editar') {
                    echo '<div class="alert alert-success" role="alert">Livro editado com sucesso.</div>';
                }
                else if ($_GET['mensagem'] == 'deletar') {
                    echo '<div class="alert alert-success" role="alert">Livro deletado com sucesso.</div>';
                }
            }
        ?>

        <h1>Cadastro de livros</h1>
        <form action="cadastro_livro.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="contactId">
            <div class="form-group">
                <label for="name">Nome do livro:</label>
                <input type="text" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Autor:</label>
                <input type="text" name="autor" required>
            </div>
            <div class="form-group">
                <label for="phone">Número de páginas:</label>
                <input type="text" name="numero_p" required>
            </div>
            <div class="form-group">
                <label for="phone">Número de páginas já lidas:</label>
                <input type="text" name="numero_pl" placeholder="Caso não tenha começado sua leitura deixe em branco">
            </div>
            <div class="form-group"><label>Já leu o livro?</label></div>
                
                Sim <input type="radio" name="status" value="sim">
                Não <input type="radio" name="status" value="nao"></br></br>
            <div class="form-group">
                <label for="file">Escolha uma imagem:</label>
                <input type="file" name="foto" id="file">
            </div>
            <div class="form-group">
            <button type="submit">Salvar</button>
            </div>
        </form>

        <!-- LIVROS CADASTRADOS -->
        <div class="container">
        <h1>Lista de livros cadastrados</h1>

        <!-- BARRA DE PESQUISA -->
        <form action="index.php" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquisar por nome do livro" name="pesquisa">
                <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
            </div>
        </form>

            <!-- LIVROS CADASTRADOS -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                require("conecta.php");

                $termo_pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

                if (!empty($termo_pesquisa)) {
                    $sql = "SELECT * FROM livros_cadastrados WHERE nome LIKE '%$termo_pesquisa%'";
                } else {
                    $sql = "SELECT * FROM livros_cadastrados";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nome = $row['nome'];
                        $autor = $row['autor'];
                        $numero_p = $row['numero_p'];
                        $numero_pl = $row['numero_pl'];
                        $status = $row['status'];
                        $foto = $row['foto'];
                        $id = $row['id'];
                    
                        echo '<div class="col">';
                        echo '<div class="card">';
                        echo '<img src="' . $foto . '" class="card-img-top" style="max-width: 300px; display: block; margin: 0 auto;" alt="Capa do Livro">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $nome . '</h5>';
                        echo '<p class="card-text">Autor(a): ' . $autor . '</p>';
                        echo '<p class="card-text">Número de páginas: ' . $numero_p . '</p>';
                        echo '<p class="card-text">Já terminou de ler: ' . $status . '</p>';
                    
                        $progresso = ($numero_pl / $numero_p) * 100;
                        $progresso_formatado = number_format($progresso, 2);

                        echo 'Progresso da leitura: '.$progresso_formatado.'%<div class="progress">';
                        echo '<div class="progress-bar" role="progressbar" style="width: ' . $progresso . '%" aria-valuenow="' . $progresso . '" aria-valuemin="0" aria-valuemax="100"></div>';
                        echo '</div>';
                    
                        echo '</br><button type="button" class="btn btn-primary me-5" onclick="openModal(' . $id . ', \'' . addslashes($nome) . '\', \'' . addslashes($autor) . '\', ' . $numero_p . ', ' . $numero_pl . ', \'' . addslashes($status) . '\')">Editar</button>';
                        echo '<button type="button" class="btn btn-danger" onclick="confirmDelete(' . $id . ')">Deletar</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum livro encontrado.</p>';
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ESTRUTURA JANELA MODAL -->
<div id="modalEditarLivro" class="modal fade" tabindex="-1" aria-labelledby="modalEditarLivroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLivroLabel">Editar Livro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form id="formEditarLivro" action="salvar_edicao.php" method="post" enctype="multipart/form-data">
          <input type="hidden" id="id" name="id">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome do livro:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>
          <div class="mb-3">
            <label for="autor" class="form-label">Autor:</label>
            <input type="text" class="form-control" id="autor" name="autor" required>
          </div>
          <div class="mb-3">
            <label for="numero_p" class="form-label">Número de páginas:</label>
            <input type="text" class="form-control" id="numero_p" name="numero_p" required>
          </div>
          <div class="mb-3">
            <label for="numero_p" class="form-label">Número de páginas lidas:</label>
            <input type="text" class="form-control" id="numero_pl" name="numero_pl" required>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <input type="text" class="form-control" id="status" name="status" required>
          </div>
          <div class="mb-3">
            <label for="foto" class="form-label">Foto:</label>
            <input type="file" class="form-control" id="foto" name="foto">
          </div>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
  </div>
</div>

<script>
    function openModal(id, nome, autor, numero_p, numero_pl, status) {
        document.getElementById('id').value = id;
        document.getElementById('nome').value = nome;
        document.getElementById('autor').value = autor;
        document.getElementById('numero_p').value = numero_p;
        document.getElementById('numero_pl').value = numero_pl;
        document.getElementById('status').value = status;

        var modal = new bootstrap.Modal(document.getElementById('modalEditarLivro'));
        modal.show();
    }

    function confirmDelete(id) {
    if (confirm("Tem certeza que deseja deletar este livro?")) {
      window.location.href = 'deletar_livro.php?id=' + id;
    }
  }
</script>
</body>
</html>

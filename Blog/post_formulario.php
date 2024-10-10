<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post | Projeto para Web com PHP</title>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css"> <!-- Link para o CSS do Bootstrap -->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    include 'includes/topo.php'; // Inclui o cabeçalho
                    include 'includes/valida_login.php'; // Verifica se o usuário está logado
                ?>
            </div>
        </div>
        <div class="row" style="min-height: 500px;">
            <div class="col-md-12">
                <?php include 'includes/menu.php'; // Inclui o menu de navegação ?>
            </div>
            <div class="col-md-10" style="padding-top: 50px;">
                <?php
                    // Inclui funções e conexão com o banco de dados
                    require_once 'includes/funcoes.php';
                    require_once 'core/conexao_mysql.php';
                    require_once 'core/sql.php';
                    require_once 'core/mysql.php';

                    // Limpa os dados recebidos pela URL
                    foreach($_GET as $indice => $dado) {
                        $$indice = limparDados($dado); // Sanitiza os dados
                    }

                    // Se o ID não estiver vazio, busca os dados do post correspondente
                    if(!empty($id)) {
                        $id = (int)$id; // Converte para inteiro

                        $criterio = [['id', '=', $id]]; // Critério de busca

                        $retorno = buscar('post', ['*'], $criterio); // Busca o post

                        $entidade = $retorno[0]; // Acessa o primeiro post retornado
                    }
                ?>
                <h2>Post</h2>
                <form action="core/post_repositorio.php" method="post"> <!-- Formulário para inserir/editar post -->
                    <input type="hidden" name="acao" value="<?php echo empty($id) ? 'insert' : 'update' ?>"> <!-- Define a ação com base na presença do ID -->
                    <input type="hidden" name="id" value="<?php echo $entidade['id'] ?? '' ?>"> <!-- ID do post, se existir -->
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" require="required" class="form-control" value="<?php echo $entidade['titulo'] ?? ''?>"> <!-- Campo para título -->
                    </div>
                    <div class="form-group">
                        <label for="texto">Texto</label>
                        <textarea name="texto" id="texto" require="required" class="form-control"><?php echo $entidade['texto'] ?? ''?></textarea> <!-- Campo para texto do post -->
                    </div>
                    <div class="form-group">
                        <label for="texto">Postar em</label>
                        <?php
                            // Obtém a data e hora da postagem, se disponíveis
                            $data = (!empty($entidade['data_postagem'])) ? explode(' ', $entidade['data_postagem'])[0] : '';
                            $hora = (!empty($entidade['data_postagem'])) ? explode(' ', $entidade['data_postagem'])[1] : '';
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <input name="data_postagem" type="date" id="data_postagem" class="form-control" value="<?php echo $data ?>"> <!-- Campo para data -->
                            </div>
                            <div class="col-md-3">
                                <input name="hora_postagem" type="time" id="hora_postagem" class="form-control" value="<?php echo $hora ?>"> <!-- Campo para hora -->
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Salvar</button> <!-- Botão para salvar -->
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php include 'includes/rodape.php' ?> <!-- Inclui o rodapé -->
            </div>
        </div>
    </div>
    <script src="lib/js/bootstrap.min.js"></script> <!-- Link para o JS do Bootstrap -->
</body>
</html>

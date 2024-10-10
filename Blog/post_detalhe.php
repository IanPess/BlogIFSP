<!DOCTYPE html>
<?php
    // Inclui funções auxiliares e conexão com o banco de dados
    require_once 'includes/funcoes.php';
    require_once 'core/conexao_mysql.php';
    require_once 'core/sql.php';
    require_once 'core/mysql.php';

    // Limpa os dados recebidos pela URL
    foreach ($_GET as $indice => $dado) {
        $$indice = limparDados($dado); // Sanitiza os dados de entrada
    }

    // Busca o post correspondente ao ID recebido
    $posts = buscar(
        'post',
        [
            'titulo',
            'data_postagem',
            'texto',
            '(select nome from usuario where usuario.id = post.usuario_id) as nome' // Obtém o nome do autor
        ],
        [
            ['id', '=', $post] // Critério para busca: ID do post
        ]
    );

    // Acessa o primeiro post retornado
    $post = $posts[0];
    
    // Formata a data do post
    $data_post = date_create($post['data_postagem']);
    $data_post = date_format($data_post, 'd/m/Y H:i:s'); // Formato: dia/mês/ano horas:minutos:segundos
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['titulo'] ?></title> <!-- Título da página -->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css"> <!-- Link para o CSS do Bootstrap -->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php include 'includes/topo.php' ?> <!-- Inclui o cabeçalho -->
            </div>
        </div>
        <div class="row" style="min-height: 500px;">
            <div class="col-md-12">
                <?php include 'includes/menu.php' ?> <!-- Inclui o menu de navegação -->
            </div>
            <div class="col-md-10" style="padding-top: 50px;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $post['titulo'] ?></h5> <!-- Título do post -->
                    <h5 class="card-subtitle mb-2 text-muted"><?php echo $data_post." Por: ".$post['nome'] ?></h5> <!-- Data e autor do post -->
                    <div class="card-text">
                        <?php echo html_entity_decode($post['texto']) ?> <!-- Conteúdo do post -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card-body">
                <a href="index.php"><button class="btn btn-success my-2 my-sm-0">Voltar ←</button></a> <!-- Botão para voltar -->
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial | Projeto para Web com PHP</title>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css"> <!-- Importa o CSS do Bootstrap -->
</head>
<body>
    <div class="container"> <!-- Container principal para layout -->
        <div class="row">
            <div class="col-md-12">
                <!-- Inclui o topo da página -->
                <?php include 'includes/topo.php' ?>
            </div>
        </div>
        <div class="row" style="min-height: 500px;"> <!-- Define altura mínima para a seção -->
            <div class="col-md-12">
                <!-- Inclui o menu de navegação -->
                <?php include 'includes/menu.php' ?>
            </div>
            <div class="col-md-10" style="padding-top: 50px;"> <!-- Espaço para o conteúdo principal -->
                <h2>Página Inicial</h2>
                <!-- Inclui a barra de busca -->
                <?php include 'includes/busca.php' ?>

                <?php
                date_default_timezone_set('America/Sao_Paulo');
                    // Inclui arquivos de funções e conexão ao banco de dados
                    require_once 'includes/funcoes.php';
                    require_once 'core/conexao_mysql.php';
                    require_once 'core/sql.php';
                    require_once 'core/mysql.php';

                    // Limpa dados de entrada
                    foreach($_GET as $indice => $dado) {
                        $$indice = limparDados($dado);
                    }

                    // Define a data atual
                    $data_atual = date('Y-m-d H:i:s');

                    // Cria um critério para buscar posts
                    $criterio = [['data_postagem', '<=', $data_atual]];

                    // Adiciona critério de busca se existir
                    if(!empty($busca)){
                        $criterio[] = [
                            'AND',
                            'titulo',
                            'like',
                            "%{$busca}%",
                        ];
                    }

                    // Busca os posts no banco de dados
                    $posts = buscar(
                        'post',
                        [
                            'titulo',
                            'data_postagem',
                            'id',
                            '(select nome from usuario where usuario.id = post.usuario_id) as nome'
                        ],
                        $criterio,
                        'data_postagem DESC'
                    );
                    
                ?>
                <div>
                    <div class="list-group"> <!-- Exibe os posts em formato de lista -->
                        <?php
                            foreach($posts as $post) :
                                $data = date_create($post['data_postagem']);
                                $data = date_format($data, 'd/m/y H:i:s'); // Formata a data
                        ?>
                        <a class="list-group-item list-group-item-action" href="post_detalhe.php?post=<?php echo $post['id']?>"> <!-- Link para detalhes do post -->
                            <strong><?php echo $post['titulo'] ?></strong>
                            <?php echo $post['nome'] ?> <!-- Nome do autor -->
                            <span class="badge badge-dark"><?php echo $data ?></span> <!-- Data formatada -->
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Inclui o rodapé da página -->
                <?php include 'includes/rodape.php' ?>
            </div>
        </div>
    </div>
    <script src="lib/js/bootstrap.min.js"></script> <!-- Importa o JS do Bootstrap -->
</body>
</html>
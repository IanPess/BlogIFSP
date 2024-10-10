<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário | Projeto para Web com PHP</title>
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
                <?php
                    // Inclui funções e conexão com o banco de dados
                    require_once 'includes/funcoes.php';
                    require_once 'core/conexao_mysql.php';
                    require_once 'core/sql.php';
                    require_once 'core/mysql.php';
                    
                    // Verifica se o usuário está logado
                    if(isset($_SESSION['login'])) {
                        $id = (int) $_SESSION['login']['usuario']['id']; // Obtém o ID do usuário logado

                        $criterio = [['id', '=', $id]]; // Critério de busca

                        // Busca os dados do usuário
                        $retorno = buscar(
                            'usuario',
                            ['id', 'nome', 'email'],
                            $criterio
                        );

                        $entidade = $retorno[0]; // Acessa o primeiro usuário retornado
                    }
                ?>
                <h2>Usuário</h2>
                <form method="post" action="core/usuario_repositorio.php"> <!-- Formulário para inserir/editar usuário -->
                    <input type="hidden" name="acao" value="<?php echo empty($id) ? 'insert' : 'update' ?>"> <!-- Define a ação com base na presença do ID -->
                    <input type="hidden" name="id" value="<?php echo $entidade['id'] ?? '' ?>"> <!-- ID do usuário, se existir -->
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" require="required" id="nome" name="nome" value="<?php echo $entidade['nome'] ?? '' ?>"> <!-- Campo para nome -->
                    </div> 
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" require="required" id="email" name="email" value="<?php echo $entidade['email'] ?? '' ?>"> <!-- Campo para e-mail -->
                    </div>
                    <?php if(!isset($_SESSION['login'])) : ?>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" require="required" id="senha" name="senha"> <!-- Campo para senha, visível apenas se o usuário não estiver logado -->
                    </div>
                    <?php endif; ?>
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

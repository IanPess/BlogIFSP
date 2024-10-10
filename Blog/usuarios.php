<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários | Projeto para Web com PHP</title>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css"> <!-- Link para o CSS do Bootstrap -->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    include 'includes/topo.php'; // Inclui o cabeçalho
                    include 'includes/valida_login.php'; // Valida o login do usuário
                    if($_SESSION['login']['usuario']['adm'] !== 1) { // Verifica se o usuário é administrador
                        header('Location: index.php'); // Redireciona se não for admin
                    }
                ?>
            </div>
        </div>
        <div class="row" style="min-height: 500px;">
            <div class="col-md-12">
                <?php include 'includes/menu.php'; // Inclui o menu ?>
            </div>
            <div class="col-md-10" style="padding-top: 50px">
                <h2>Usuário</h2>
                <?php include 'includes/busca.php'; // Inclui a barra de busca ?>
                <?php
                    require_once 'includes/funcoes.php';
                    require_once 'core/conexao_mysql.php';
                    require_once 'core/sql.php';
                    require_once 'core/mysql.php';

                    foreach($_GET as $indice => $dado) {
                        $$indice = limparDados($dado); // Limpa os dados recebidos via GET
                    }

                    $data_atual = date('Y-m-d H:i:s'); // Data e hora atual

                    $criterio = [];

                    if(!empty($busca)) {
                        $criterio[] = ['email', 'like', "%{$busca}%"]; // Filtra usuários pelo nome, se houver busca
                    }

                    // Busca usuários com os critérios definidos
                    $result = buscar('usuario', ['id', 'nome', 'email', 'data_criacao', 'ativo', 'adm'], $criterio, 'data_criacao DESC, nome ASC');
                ?>
                <table class="table table-bordered table-hover table-striped table-responsive"> <!-- Tabela para exibir usuários -->
                    <thead>
                        <tr>
                            <td>Nome</td>
                            <td>E-mail</td>
                            <td>Data Cadastro</td>
                            <td>Ativo</td>
                            <td>Administrador</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($result as $entidade) : // Percorre os resultados
                                $data = date_create($entidade['data_criacao']); // Converte a data de criação
                                $data = date_format($data, 'd/m/Y H:i:s'); // Formata a data
                        ?>
                        <tr>
                            <td><?php echo $entidade['nome'] ?></td>
                            <td><?php echo $entidade['email'] ?></td>
                            <td><?php echo $data ?></td>
                            <td><a href="core/usuario_repositorio.php?acao=status&id=<?php echo $entidade['id'] ?>&valor=<?php echo !$entidade['ativo']?>"><?php echo ($entidade['ativo']==1) ? 'Desativar' : 'Ativar'; ?></a></td>
                            <td><a href="core/usuario_repositorio.php?acao=adm&id=<?php echo $entidade['id'] ?>&valor=<?php echo !$entidade['adm']?>"><?php echo ($entidade['adm']==1) ? 'Rebaixar' : 'Promover'; ?></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

<?php
session_start(); // Inicia a sessão para gerenciar dados do usuário
require_once '../includes/funcoes.php'; // Inclui funções auxiliares
require_once 'conexao_mysql.php'; // Inclui a conexão com o banco de dados
require_once 'sql.php'; // Inclui funções para manipulação de SQL
require_once 'mysql.php'; // Inclui funções específicas para o MySQL

$salt = '$exemplosaltifsp'; // Define um salt para hash da senha

// Limpa e atribui dados recebidos pelo método POST
foreach ($_POST as $indice => $dado) {
    $$indice = limparDados($dado); // Limpa os dados para evitar injeções
}

// Limpa e atribui dados recebidos pelo método GET
foreach ($_GET as $indice => $dado) {
    $$indice = limparDados($dado);
}

// Switch para decidir a ação com base na variável $acao
switch($acao) {
    case 'insert': // Inserção de um novo usuário
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => crypt($senha, $salt) // Criptografa a senha com salt
        ];
        print_r($dados); // Imprime os dados (provavelmente para debug)
        insere('usuario', $dados); // Insere o usuário na tabela

        break;

    case 'update': // Atualização de um usuário existente
        $id = (int)$id; // Converte o ID para inteiro
        $dados = [
            'nome' => $nome,
            'email' => $email
        ];

        $criterio = [['id', '=', $id]]; // Define o critério de atualização

        atualiza('usuario', $dados, $criterio); // Atualiza o usuário

        break;

    case 'login': // Processa o login do usuário
        $criterio = [
            ['email', '=', $email],
            ["AND", 'ativo', '=', 1] // Verifica se o usuário está ativo
        ];

        // Busca o usuário no banco de dados
        $retorno = buscar('usuario', ['id', 'nome', 'email', 'senha', 'adm'], $criterio);
        
        if(count($retorno) > 0) { // Se o usuário existe
            // Verifica se a senha fornecida corresponde à senha armazenada
            if (crypt($senha, $salt) == $retorno[0]['senha']) {
                $_SESSION['login']['usuario'] = $retorno[0]; // Armazena informações do usuário na sessão
                if(!empty($_SESSION['url_retorno'])) {
                    header('Location: ' . $_SESSION['url_retorno']); // Redireciona para a URL de retorno
                    $_SESSION['url_retorno'] = ''; // Limpa a URL de retorno
                    exit;
                }
            }
        }

        break;

    case 'logout': // Processa o logout do usuário
        session_destroy(); // Destroi a sessão, efetivamente desconectando o usuário
        break;

    case 'status': // Atualiza o status (ativo/inativo) do usuário
        $id = (int)$id; // Converte o ID para inteiro
        $valor = (int)$valor; // Converte o valor (0 ou 1)

        $dados = ['ativo' => $valor]; // Prepara os dados para atualização

        $criterio = [['id', '=', $id]]; // Define o critério de atualização

        atualiza('usuario', $dados, $criterio); // Atualiza o status do usuário

        header('Location: ../usuarios.php'); // Redireciona para a página de usuários
        exit;
        break;

    case 'adm': // Atualiza o status de administrador do usuário
        $id = (int)$id; // Converte o ID para inteiro
        $valor = (int)$valor; // Converte o valor (0 ou 1)

        $dados = ['adm' => $valor]; // Prepara os dados para atualização

        $criterio = [['id', '=', $id]]; // Define o critério de atualização

        atualiza('usuario', $dados, $criterio); // Atualiza o status de administrador do usuário

        header('Location: ../usuarios.php'); // Redireciona para a página de usuários
        exit;
        break;
}

// Redireciona para a página inicial após a execução da ação
header('Location: ../index.php');
?>

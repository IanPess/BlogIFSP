<?php
session_start(); // Inicia a sessão para gerenciar dados do usuário
require_once '../includes/valida_login.php'; // Inclui validação de login
require_once '../includes/funcoes.php'; // Inclui funções auxiliares
require_once 'conexao_mysql.php'; // Inclui a conexão com o banco de dados
require_once 'sql.php'; // Inclui funções para manipulação de SQL
require_once 'mysql.php'; // Inclui funções específicas para o MySQL

// Limpa e atribui dados recebidos pelo método POST
foreach($_POST as $indice => $dado) {
    $$indice = limparDados($dado); // Limpa os dados para prevenir injeções
}

// Limpa e atribui dados recebidos pelo método GET
foreach($_GET as $indice => $dado) {
    $$indice = limparDados($dado);
}

// Converte o ID para um inteiro
$id = (int)$id;

switch($acao) { // Decide a ação a ser realizada
    case 'insert': // Inserção de um novo post
        $dados = [
            'titulo' => $titulo,
            'texto' => $texto,
            'data_postagem' => "$data_postagem $hora_postagem", // Combina data e hora
            'usuario_id' => $_SESSION['login']['usuario']['id'] // Obtém o ID do usuário logado
        ];

        insere (
            'post', // Nome da tabela
            $dados // Dados a serem inseridos
        );

        break;
        
    case 'update': // Atualização de um post existente
        $dados = [
            'titulo' => $titulo,
            'texto' => $texto,
            'data_postagem' => "$data_postagem $hora_postagem", // Combina data e hora
            'usuario_id' => $_SESSION['login']['usuario']['id'] // Obtém o ID do usuário logado
        ];

        $criterio = [
            ['id', '=', $id] // Define o critério para atualizar o post correto
        ];

        atualiza (
            'post', // Nome da tabela
            $dados, // Dados a serem atualizados
            $criterio // Critério de atualização
        );

        break;

    case 'delete': // Deletar um post
        $criterio = [
            ['id', '=', $id] // Define o critério para deletar o post correto
        ];

        deleta (
            'post', // Nome da tabela
            $criterio // Critério de deleção
        );

        break;
}

// Redireciona o usuário de volta para a página inicial
header('Location: ../index.php');

?>

<?php
// Função para inserir dados em uma tabela
function insere(string $entidade, array $dados) : bool {
    $retorno = false; // Inicializa a variável de retorno como falsa

    foreach ($dados as $campo => $dado) {
        $coringa[$campo] = '?'; // Define os placeholders para os campos
        $tipo[] = gettype($dado)[0]; // Armazena o tipo do dado para o bind
        $$campo = $dado; // Cria variáveis dinâmicas (não recomendado em geral)
    }

    $instrucao = insert($entidade, $coringa); // Cria a instrução SQL para inserir
    echo $instrucao; // Exibe a instrução SQL (útil para debugging)
    $conexao = conecta(); // Conecta ao banco de dados

    // Prepara a instrução SQL
    $stmt = mysqli_prepare($conexao, $instrucao);

    // Usa eval para criar a chamada de bind_param (potencialmente inseguro)
    eval('mysqli_stmt_bind_param($stmt, \'' . implode('', $tipo) . '\',$' . implode(', $', array_keys($dados)) . ');');

    // Executa a instrução
    mysqli_stmt_execute($stmt);

    $retorno = (boolean) mysqli_stmt_affected_rows($stmt); // Verifica se a operação afetou linhas

    // Armazena erros da operação na sessão
    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt); // Fecha a instrução
    desconecta($conexao); // Desconecta do banco

    return $retorno; // Retorna o status da operação
}

// Função para atualizar dados em uma tabela
function atualiza(string $entidade, array $dados, array $criterio = []) : bool {
    $retorno = false; // Inicializa a variável de retorno

    // Prepara os dados para o bind
    foreach ($dados as $campo => $dado) {
        $coringa_dados[$campo] = '?';
        $tipo[] = gettype($dado)[0];
        $$campo = $dado;
    }

    // Prepara os critérios para a cláusula WHERE
    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;

        // Cria um nome único para o campo
        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];
        if (isset($nome_campo)) {
            $nome_campo = $nome_campo . '_' . rand();
        }
        $campos_criterio[] = $nome_campo;
        $$nome_campo = $dado;
    }

    $instrucao = update($entidade, $coringa_dados, $coringa_criterio); // Cria a instrução SQL

    $conexao = conecta(); // Conecta ao banco

    $stmt = mysqli_prepare($conexao, $instrucao); // Prepara a instrução

    // Cria o comando de bind_param usando eval (potencialmente inseguro)
    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt,' . "'" . implode('', $tipo) . "'" . ', $' . implode(', $', array_keys($dados)) . ', $' . implode(', $', $campos_criterio) . ');';
        eval($comando);
    }

    mysqli_stmt_execute($stmt); // Executa a instrução

    $retorno = (boolean) mysqli_stmt_affected_rows($stmt); // Verifica se a operação afetou linhas

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt); // Armazena erros da operação

    mysqli_stmt_close($stmt); // Fecha a instrução
    desconecta($conexao); // Desconecta do banco

    return $retorno; // Retorna o status da operação
}

// Função para deletar dados em uma tabela
function deleta(string $entidade, array $criterio = []) : bool {
    $retorno = false; // Inicializa a variável de retorno
    $coringa_criterio = []; // Inicializa o array para os critérios

    // Prepara os critérios para a cláusula WHERE
    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;

        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];
        $campos_criterio[] = $nome_campo;
        $$nome_campo = $dado;
    }

    $instrucao = delete($entidade, $coringa_criterio); // Cria a instrução SQL

    $conexao = conecta(); // Conecta ao banco
    $stmt = mysqli_prepare($conexao, $instrucao); // Prepara a instrução

    // Cria o comando de bind_param
    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt,' . "'" . implode('', $tipo) . "'" . ', $' . implode(', $', $campos_criterio) . ');';
        eval($comando);
    }

    mysqli_stmt_execute($stmt); // Executa a instrução

    $retorno = (boolean) mysqli_stmt_affected_rows($stmt); // Verifica se a operação afetou linhas

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt); // Armazena erros da operação
    mysqli_stmt_close($stmt); // Fecha a instrução
    desconecta($conexao); // Desconecta do banco

    return $retorno; // Retorna o status da operação
}

// Função para buscar dados em uma tabela
function buscar(string $entidade, array $campos = ['*'], array $criterio = [], string $ordem = null) : array {
    $retorno = false; // Inicializa a variável de retorno
    $coringa_criterio = []; // Inicializa o array para os critérios

    // Prepara os critérios para a cláusula WHERE
    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipo[] = gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;

        $nome_campo = (count($expressao) < 4) ? $expressao[0] : $expressao[1];
        if (isset($$nome_campo)) {
            $nome_campo = $nome_campo . '_' . rand();
        }
        $campos_criterio[] = $nome_campo;
        $$nome_campo = $dado;
    }

    $instrucao = select($entidade, $campos, $coringa_criterio, $ordem); // Cria a instrução SQL

    $conexao = conecta(); // Conecta ao banco
    $stmt = mysqli_prepare($conexao, $instrucao); // Prepara a instrução

    // Cria o comando de bind_param
    if (isset($tipo)) {
        $comando = 'mysqli_stmt_bind_param($stmt,' . "'" . implode('', $tipo) . "'" . ', $' . implode(', $', $campos_criterio) . ');';
        eval($comando);
    }

    mysqli_stmt_execute($stmt); // Executa a instrução

    if ($result = mysqli_stmt_get_result($stmt)) {
        $retorno = mysqli_fetch_all($result, MYSQLI_ASSOC); // Busca todos os resultados
        mysqli_free_result($result); // Libera o resultado
    }

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt); // Armazena erros da operação
    mysqli_stmt_close($stmt); // Fecha a instrução

    return $retorno; // Retorna os resultados
}
?>

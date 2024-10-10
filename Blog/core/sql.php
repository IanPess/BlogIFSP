<?php
function insert(string $entidade, array $dados) : string {
    // Cria a instrução SQL para inserção
    $instrucao = "INSERT INTO {$entidade}";

    // Extrai os campos e valores da array $dados
    $campos = implode(', ', array_keys($dados));
    $valores = implode(', ', array_values($dados));

    // Monta a instrução SQL completa
    $instrucao .= " ({$campos})";
    $instrucao .= " VALUES ({$valores})";

    return $instrucao; // Retorna a instrução SQL
}

function update(string $entidade, array $dados, array $criterio = []) : string {
    // Cria a instrução SQL para atualização
    $instrucao = "UPDATE {$entidade}";

    // Monta a parte SET da instrução SQL
    foreach($dados as $campo => $dado) {
        $set[] = "{$campo} = {$dado}"; // Adiciona cada campo e seu novo valor
    }

    $instrucao .= ' SET ' . implode(', ', $set); // Adiciona a parte SET à instrução

    // Adiciona a cláusula WHERE, se houver critérios
    if(!empty($criterio)){
        $instrucao .= ' WHERE ';
        foreach($criterio as $expressao) {
            $instrucao .= ' ' . implode(' ', $expressao); // Monta a cláusula WHERE
        }
    }

    return $instrucao; // Retorna a instrução SQL
}

function delete(string $entidade, array $criterio = []) {
    // Cria a instrução SQL para deleção
    $instrucao = "DELETE FROM {$entidade}";

    // Adiciona a cláusula WHERE, se houver critérios
    if(!empty($criterio)) {
        $instrucao .= ' WHERE ';
        foreach($criterio as $expressao) {
            $instrucao .= ' ' . implode(' ', $expressao); // Monta a cláusula WHERE
        }
    }

    return $instrucao; // Retorna a instrução SQL
}

function select(string $entidade, array $campos, array $criterio = [], string $ordem = null) : string {
    // Cria a instrução SQL para seleção de dados
    $instrucao = "SELECT " . implode(', ', $campos);
    $instrucao .= " FROM {$entidade}";

    // Adiciona a cláusula WHERE, se houver critérios
    if(!empty($criterio)) {
        $instrucao .= ' WHERE ';
        foreach($criterio as $expressao) {
            $instrucao .= ' ' . implode(' ', $expressao); // Monta a cláusula WHERE
        }
    }

    // Adiciona a cláusula ORDER BY, se houver
    if(!empty($ordem)) {
        $instrucao .= " ORDER BY $ordem ";
    }

    return $instrucao; // Retorna a instrução SQL
}
?>

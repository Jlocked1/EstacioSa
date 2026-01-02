<?php
function buscar_usuario_id($mysqli, $id)
{

    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo "Erro ao preparar query: " . $mysqli->error;
        return null;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $stmt->close();

    return $usuario;
}

function buscar_usuario_email($mysqli, $email)
{

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo "Erro ao preparar query: " . $mysqli->error;
        return null;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $stmt->close();

    return $usuario;
}

function listar_todos_usuarios($mysqli)
{

    $sql = "SELECT * FROM usuarios ORDER BY nome ASC";
    $result = $mysqli->query($sql);

    if (!$result) {
        error_log("Erro ao listar usuarios: " . $mysqli->error);
        return [];
    }

    $usuarios = $result->fetch_all(MYSQLI_ASSOC);

    return $usuarios;
}

function email_existe($mysqli, $email, $excluir_id = null)
{

    if ($excluir_id !== null) {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE email = ? AND id != ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $email, $excluir_id);
    } else {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['total'] > 0;
}

function criar_usuario($mysqli, $dados)
{

    $campos_obrigatorios = ['nome', 'email', 'senha'];
    foreach ($campos_obrigatorios as $campo) {
        if (empty($dados[$campo])) {
            error_log("Campo obrigatório faltando: $campo");
            return false;
        }
    }

    // se email é válido
    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        error_log("Email inválido: " . $dados['email']);
        return false;
    }

    // se email já existe
    if (email_existe($mysqli, $dados['email'])) {
        error_log("Email já existe: " . $dados['email']);
        return false;
    }

    $senha_hash = password_hash($dados['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (
        nome, email, senha, adm, 
        cadeira1, cadeira2, cadeira3, 
        professor1, professor2, professor3, 
        notacadeira1, notacadeira2, notacadeira3, 
        faltas
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        error_log("Erro ao preparar INSERT: " . $mysqli->error);
        return false;
    }

    // Valores padrão para campos opcionais
    $nome = $dados['nome'];
    $email = $dados['email'];
    $adm = $dados['admin'] ?? 0;

    $cadeira1 = $dados['cadeira1'] ?? '';
    $professor1 = $dados['professor1'] ?? '';
    $notacadeira1 = $dados['notacadeira1'] ?? 0.0;

    $cadeira2 = $dados['cadeira2'] ?? '';
    $professor2 = $dados['professor2'] ?? '';
    $notacadeira2 = $dados['notacadeira2'] ?? 0.0;

    $cadeira3 = $dados['cadeira3'] ?? '';
    $professor3 = $dados['professor3'] ?? '';
    $notacadeira3 = $dados['notacadeira3'] ?? 0.0;

    $faltas = $dados['faltas'] ?? 0;

    $stmt->bind_param(
        "sssissssssdddi",
        $nome, $email, $senha_hash, $adm,
        $cadeira1, $cadeira2, $cadeira3,
        $professor1, $professor2, $professor3,
        $notacadeira1, $notacadeira2, $notacadeira3,
        $faltas
    );


    $sucesso = $stmt->execute();

    if (!$sucesso) {
        error_log("Erro ao executar INSERT: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $novo_id = $mysqli->insert_id;
    $stmt->close();

    return $novo_id;
}

function atualizar_usuario($mysqli, $id, $dados)
{

    $usuario_atual = buscar_usuario_id($mysqli, $id);

    // Verifica se o usuário existe
    if (!$usuario_atual) {
        error_log("Usuário não encontrado para atualização: ID $id");
        return false;
    }

    if (isset($dados['email']) && $dados['email'] !== $usuario_atual['email']) {
        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            error_log("Email inválido: " . $dados['email']);
            return false;
        }

        if (email_existe($mysqli, $dados['email'], $id)) {
            error_log("Email já existe: " . $dados['email']);
            return false;
        }
    }

    $campos = [];
    $tipos = "";
    $valores = [];

    $campos_permitidos = [
        'nome',
        'email',
        'adm',
        'cadeira1',
        'professor1',
        'notacadeira1',
        'cadeira2',
        'professor2',
        'notacadeira2',
        'cadeira3',
        'professor3',
        'notacadeira3',
        'faltas'
    ];

    foreach ($campos_permitidos as $campo) {
        if (isset($dados[$campo])) {
            $campos[] = "$campo = ?";

            if (in_array($campo, ['notacadeira1', 'notacadeira2', 'notacadeira3'])) {
                $tipos .= "d";
                $valores[] = floatval($dados[$campo]);
            } elseif (in_array($campo, ['adm', 'faltas'])) {
                $tipos .= "i";
                $valores[] = intval($dados[$campo]);
            } else {
                $tipos .= "s";
                $valores[] = $dados[$campo];
            }
        }
    }

    // Se tem senha nova, fazer hash
    if (!empty($dados['senha'])) {
        $campos[] = "senha = ?";
        $tipos .= "s";
        $valores[] = password_hash($dados['senha'], PASSWORD_DEFAULT);
    }

    // Se não tem nada pra atualizar
    if (empty($campos)) {
        return true; // Não é erro, só não tinha nada para fazer
    }

    // Adicionar ID no final
    $tipos .= "i";
    $valores[] = $id;

    $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        error_log("Erro ao preparar UPDATE: " . $mysqli->error);
        return false;
    }

    $stmt->bind_param($tipos, ...$valores);
    $sucesso = $stmt->execute();

    if (!$sucesso) {
        error_log("Erro ao executar UPDATE: " . $stmt->error);
    }

    $stmt->close();

    return $sucesso;
}

function deletar_usuario($mysqli, $id)
{

    $usuario = buscar_usuario_id($mysqli, $id);
    if (!$usuario) {
        error_log("Usuário não encontrado para deleção: ID $id");
        return false;
    }

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        error_log("Erro ao preparar DELETE: " . $mysqli->error);
        return false;
    }

    $stmt->bind_param("i", $id);
    $sucesso = $stmt->execute();

    if (!$sucesso) {
        error_log("Erro ao executar DELETE: " . $stmt->error);
    }

    $stmt->close();

    return $sucesso;
}

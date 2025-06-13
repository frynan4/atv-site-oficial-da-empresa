<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    // Configurações do banco de dados
    $host = "localhost";
    $user = "root"; // usuário padrão
    $password = ""; // senha em branco
    $database = "nome_do_banco"; // nome do banco de dados

    // Conectar ao banco
    $conn = new mysqli($host, $user, $password, $database);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Receber dados do formulário
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Verificar se o campo de login foi enviado
        if (!empty($dados["sendlogin"])) {
            // Preparar consulta SQL para verificar usuário
            $query_usuario = "SELECT id, senha FROM usuarios WHERE usuario = ? LIMIT 1";
            $stmt = $conn->prepare($query_usuario);
            $stmt->bind_param("s", $dados["usuario"]);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Verificar se o usuário foi encontrado
            if ($resultado->num_rows == 1) {
                // Usuário encontrado, verificar a senha
                $row_usuario = $resultado->fetch_assoc();
                if (password_verify($dados["senha_usuario"], $row_usuario["senha"])) {
                    // Senha correta, redirecionar
                    session_start();
                    $_SESSION['ID'] = $row_usuario['id'];
                    $_SESSION['usuario'] = $dados["usuario"];

                    header("Location: dashboard.php"); // Redireciona para a página do painel
                    exit();
                } else {
                    echo "<p style='color:red'>Erro: senha incorreta!</p>";
                }
            } else {
                echo "<p style='color:red'>Erro: usuário não encontrado!</p>";
            }
        }
    }
    ?>

    <!-- Formulário de login -->
    <form method="POST" action="">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required><br>

        <label for="senha_usuario">Senha:</label>
        <input type="password" name="senha_usuario" id="senha_usuario" required><br>

        <input type="submit" name="sendlogin" value="Login">
    </form>
</body>
</html>

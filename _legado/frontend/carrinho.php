<?php

// Verifica se o usuário está autenticado
if (!isset($_SESSION['token'])) {
    header("Location: index.php"); // Redireciona para o index se não estiver autenticado
    exit;
}

// Aqui você pode colocar o conteúdo da sua página protegida
?>
<!DOCTYPE html>
<html>
<head>
    <title>Página Protegida</title>
</head>
<body>
    <h1>Bem-vindo à Página Protegida</h1>
    <? print_r($_SESSION) ?>
</body>
</html>

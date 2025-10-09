<?php

$saldoInicial = 500;
$conta = new Depositar($saldoInicial);
$usuario = new Usuario(1, "David", $conta);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST['acao'] ?? '';
    $valor = floatval($_POST['valor'] ?? 0);

    if ($acao === 'sacar') {
        $sacar = new Sacar($conta->consultar());
        $sacar->sacar($valor);
        $conta = new Depositar($sacar->consultar());
    } elseif ($acao === 'depositar') {
        $conta->depositar($valor);
    }

    $usuario = new Usuario(1, "David", $conta);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Conta Strategy - PHP</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6fa;
        color: #333;
        text-align: center;
        margin: 0;
        padding: 20px;
    }
    h1 {
        color: #4A446E;
    }
    .container {
        background: white;
        border-radius: 10px;
        width: 400px;
        margin: 30px auto;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    input[type="number"] {
        width: 80%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
    }
    button {
        background-color: #4A446E;
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: 0.3s;
    }
    button:hover {
        background-color: #372f58;
    }
    .success {
        color: green;
        font-weight: bold;
    }
    .error {
        color: red;
        font-weight: bold;
    }
    .saldo {
        font-size: 20px;
        font-weight: bold;
        color: #4A446E;
        margin-top: 20px;
    }
</style>
</head>
<body>
    <h1>Carteira Digital - Padrão Strategy</h1>
    <div class="container">
        <p>Usuário: <strong><?= $usuario->getNome() ?></strong></p>
        <p class="saldo">Saldo atual: R$ <?= number_format($usuario->getConta()->consultar(), 2, ',', '.') ?></p>

        <form method="POST">
            <input type="number" step="0.01" name="valor" placeholder="Informe o valor" required>
            <div>
                <button type="submit" name="acao" value="depositar">Depositar</button>
                <button type="submit" name="acao" value="sacar">Sacar</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['carteira'])) {
    $_SESSION['carteira'] = (new Carteira(usuarioID: 1, saldo: 500.00))->toArray();
}
if (!isset($_SESSION['repo'])) {
    $_SESSION['repo'] = []; 
}

$carteira = Carteira::fromArray($_SESSION['carteira']);
$repo     = Repositorio::fromArray($_SESSION['repo']);

$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao  = $_POST['acao'] ?? '';
    $valor = isset($_POST['valor']) ? (float)$_POST['valor'] : 0.0;

    try {
        switch ($acao) {
            case 'depositar':
                $carteira->depositar($valor);
                $flash = ['ok', "Depósito de R$ " . number_format($valor, 2, ',', '.') . " realizado."];
                break;
            case 'sacar':
                $carteira->sacar($valor);
                $flash = ['ok', "Saque de R$ " . number_format($valor, 2, ',', '.') . " realizado."];
                break;
            case 'salvar':
                $repo->salvaEstado($carteira->salvarExtrato());
                $flash = ['ok', "Estado salvo no repositório (checkpoint)."];
                break;
            case 'desfazer':
                $repo->desfazer();
                $topo = $repo->topo();
                if ($topo) {
                    $carteira->restaurar($topo);
                    $flash = ['ok', "Estado restaurado a partir do último checkpoint."];
                } else {
                    $flash = ['warn', "Nenhum checkpoint para restaurar."];
                }
                break;
            case 'resetar':
                $carteira = new Carteira(1, 500.00, []);
                $repo     = new Repositorio();
                $flash    = ['ok', "Ambiente limpo. Saldo reiniciado para R$ 500,00."];
                break;
        }
    } catch (Throwable $e) {
        $flash = ['err', $e->getMessage()];
    }

    $_SESSION['carteira'] = $carteira->toArray();
    $_SESSION['repo']     = $repo->toArray();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Memento • Carteira/Repositorio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root {
  --bg:#f6f7fb; --card:#fff; --text:#2d2a3b; --muted:#6b6b7a;
  --brand:#4A446E; --brand-dark:#3b355b; --ok:#0a7a28; --err:#b00020; --warn:#b08900;
}
* { box-sizing: border-box; }
body { margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Open Sans',sans-serif; background:var(--bg); color:var(--text);}
.container { max-width:960px; margin:40px auto; padding:0 16px; }
h1 { color:var(--brand); font-weight:800; letter-spacing:.2px; margin:0 0 20px;}
.grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.card { background:var(--card); border-radius:14px; box-shadow:0 8px 30px rgba(0,0,0,.06); padding:20px; }
.saldo { font-size:28px; font-weight:800; color:var(--brand); margin:4px 0 16px;}
small.muted { color:var(--muted); }
label { display:block; font-size:14px; margin-bottom:6px; color:var(--muted); }
input[type=number] { width:100%; padding:12px 14px; border:1px solid #e2e2ea; border-radius:10px; font-size:16px; }
.row { display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; }
button { border:0; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:700; }
.btn { background:var(--brand); color:#fff; }
.btn:hover { background:var(--brand-dark); }
.btn-ghost { background:#ececfa; color:var(--brand); }
.btn-danger { background:#fde7ea; color:var(--err); }
.badge { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; }
.ok { background:#e7f7ec; color:var(--ok); }
.err { background:#fde7ea; color:var(--err); }
.warn{ background:#fff6db; color:var(--warn);}
pre { background:#0f1224; color:#d7e3ff; padding:14px; border-radius:12px; overflow:auto; max-height:240px; }
ul.checks { margin:0; padding-left:18px; color:var(--muted); }
@media (max-width:800px){ .grid{grid-template-columns:1fr;} }
</style>
</head>
<body>
<div class="container">
  <h1>Carteira Digital — Padrão Memento</h1>

  <?php if ($flash): ?>
    <div class="card" style="margin-bottom:14px">
      <span class="badge <?=htmlspecialchars($flash[0])?>"><?=htmlspecialchars(strtoupper($flash[0]))?></span>
      <div style="margin-top:8px"><?=htmlspecialchars($flash[1])?></div>
    </div>
  <?php endif; ?>

  <div class="grid">
    <!-- Estado atual da Carteira -->
    <div class="card">
      <div><small class="muted">Usuário</small><div style="font-weight:700">#1</div></div>
      <div class="saldo">Saldo atual: R$ <?=number_format($carteira->consultarSaldo(), 2, ',', '.')?></div>

      <form method="post" class="op">
        <label for="valor">Valor</label>
        <input type="number" id="valor" name="valor" step="0.01" min="0" placeholder="0,00">
        <div class="row">
          <button class="btn" type="submit" name="acao" value="depositar">Depositar</button>
          <button class="btn" type="submit" name="acao" value="sacar">Sacar</button>
          <button class="btn-ghost" type="submit" name="acao" value="salvar" title="Cria checkpoint">Salvar estado</button>
          <button class="btn-ghost" type="submit" name="acao" value="desfazer" title="Restaura do último checkpoint">Desfazer (undo)</button>
          <button class="btn-danger" type="submit" name="acao" value="resetar">Resetar</button>
        </div>
      </form>

      <div style="margin-top:18px">
        <small class="muted">Extrato</small>
        <pre><?=htmlspecialchars($carteira->consultarExtrato())?></pre>
      </div>
    </div>

    <!-- Repositório (pilha de mementos) -->
    <div class="card">
      <h3 style="margin:0 0 10px">Repositório (checkpoints)</h3>
      <?php
        $repoArray = $repo->toArray();
        if (!$repoArray) {
            echo '<small class="muted">Nenhum estado salvo ainda. Clique em “Salvar estado”.</small>';
        } else {
            echo '<ul class="checks">';
            foreach ($repoArray as $i => $m) {
                $idx = $i + 1;
                echo "<li>#$idx • Saldo: R$ " . number_format((float)$m['saldo'], 2, ',', '.') .
                     " • Transações: " . count($m['transacoes']) . "</li>";
            }
            echo '</ul>';
        }
      ?>
      <p style="margin-top:12px"><small class="muted">
        A pilha é LIFO: cada “Salvar estado” empilha um novo <em>Memento</em>.<br>
        “Desfazer” remove o topo e restaura o novo topo.
      </small></p>
    </div>
  </div>
</div>
</body>
</html>

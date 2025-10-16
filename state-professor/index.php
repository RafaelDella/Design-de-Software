<?php

session_start();

if (!isset($_SESSION['professor'])) {
    $_SESSION['professor'] = new Professor('Carlos Souza');
}

/** @var Professor $prof */
$prof = $_SESSION['professor'];

switch ($prof->getSituacao()) {
    case 'A': $prof->setEstado(new Atuando($prof)); break;
    case 'F': $prof->setEstado(new Ferias($prof)); break;
    case 'D': $prof->setEstado(new Demitido($prof)); break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        match ($action) {
            'atuar' => $prof->atuar(),
            'ferias' => $prof->tirarFerias(),
            'demitir' => $prof->demitir(),
            'reset' => $_SESSION['professor'] = $prof = new Professor('Carlos Souza'),
            default => null
        };
        $_SESSION['flash'] = ['ok' => true, 'msg' => 'Operação realizada com sucesso.'];
    } catch (Throwable $e) {
        $_SESSION['flash'] = ['ok' => false, 'msg' => $e->getMessage()];
    }
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}

$estado = $prof->getEstado();
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Professor — State Pattern</title>
  <style>
    body{background:#0f1221;color:#eef1ff;font-family:system-ui,Arial;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
    .card{background:#171a2e;padding:24px;border-radius:18px;box-shadow:0 10px 25px rgba(0,0,0,.3);width:min(480px,92vw)}
    h1{margin:0 0 1rem;font-weight:800}
    .state{display:inline-block;padding:.25rem .6rem;border-radius:999px;font-weight:700}
    .A{background:#3a6ff7}.F{background:#ff9f1c}.D{background:#ef476f}
    form{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
    button{border:0;border-radius:10px;padding:10px 14px;font-weight:700;cursor:pointer;background:#2a315a;color:#fff}
    button.primary{background:#4654ff}
    button.warn{background:#ff9f1c}
    button.danger{background:#ef476f}
    button:disabled{opacity:.4;cursor:not-allowed}
    .flash{margin-bottom:12px;padding:10px 14px;border-radius:10px}
    .flash.ok{background:rgba(103,211,139,.12);border:1px solid rgba(103,211,139,.35)}
    .flash.err{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.35)}
  </style>
</head>
<body>
  <div class="card">
    <h1>Professor <?= htmlspecialchars($prof->getNome()) ?> — <span class="state <?= htmlspecialchars($prof->getSituacao()) ?>"><?= htmlspecialchars($estado->label()) ?></span></h1>

    <?php if ($flash): ?>
      <div class="flash <?= $flash['ok'] ? 'ok' : 'err' ?>"><?= htmlspecialchars($flash['msg']) ?></div>
    <?php endif; ?>

    <form method="post">
      <button name="action" value="atuar" class="primary" <?= $prof->getSituacao()==='F' ? '' : 'disabled' ?>>Atuar</button>
      <button name="action" value="ferias" class="warn" <?= $prof->getSituacao()==='A' ? '' : 'disabled' ?>>Férias</button>
      <button name="action" value="demitir" class="danger" <?= $prof->getSituacao()!=='D' ? '' : 'disabled' ?>>Demitir</button>
      <button name="action" value="reset">Resetar exemplo</button>
    </form>

    <p style="opacity:.7;margin-top:10px">Regras: De <strong>Atuando</strong> pode ir para <strong>Férias</strong> ou <strong>Demitido</strong>. De <strong>Férias</strong> pode retornar a <strong>Atuando</strong> ou ser <strong>Demitido</strong>. <strong>Demitido</strong> não permite novas transições.</p>
  </div>
</body>
</html>

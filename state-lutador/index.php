<?php

session_start();

if (!isset($_SESSION['luta'])) {
    $_SESSION['luta'] = new Luta(['Lutador A', 'Lutador B']);
}

/** @var Luta $luta */
$luta = $_SESSION['luta'];

switch ($luta->getSituacao()) {
    case 'A': $luta->setEstado(new Agendada($luta)); break;
    case 'F': $luta->setEstado(new Finalizado($luta)); break;
    case 'C': $luta->setEstado(new Cancelada($luta)); break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        match ($action) {
            'agendar' => $luta->agendar(),
            'finalizar' => $luta->finalizar(),
            'cancelar' => $luta->cancelar(),
            'reset' => $_SESSION['luta'] = $luta = new Luta(['Lutador A', 'Lutador B']),
            default => null
        };
        $_SESSION['flash'] = ['ok' => true, 'msg' => 'Operação realizada com sucesso.'];
    } catch (Throwable $e) {
        $_SESSION['flash'] = ['ok' => false, 'msg' => $e->getMessage()];
    }
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}

$estado = $luta->getEstado();
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);

?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Luta — State Pattern</title>
  <style>
    :root { --bg:#0f1221; --card:#171a2e; --muted:#aab1c7; --ok:#67d38b; --warn:#ffd166; --err:#ff6b6b; }
    *{box-sizing:border-box;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Inter,Arial}
    body{margin:0;background:linear-gradient(180deg,#0b0e1a,#0f1221);color:#eef1ff;min-height:100vh;display:flex;align-items:center;justify-content:center}
    .wrap{width:min(900px,92vw)}
    .card{background:var(--card);border-radius:18px;padding:24px;box-shadow:0 12px 30px rgba(0,0,0,.35)}
    h1{margin:.2rem 0 1rem;font-weight:800;letter-spacing:.3px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .box{background:#12162a;border:1px solid #242842;border-radius:14px;padding:16px}
    .muted{color:var(--muted);font-size:.95rem}
    .state{display:inline-block;padding:.25rem .6rem;border-radius:999px;font-weight:700}
    .state.A{background:#293a7d}
    .state.F{background:#1f6f51}
    .state.C{background:#7d2b2b}
    form{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
    button{border:0;border-radius:10px;padding:10px 14px;font-weight:700;cursor:pointer;background:#2a315a;color:#fff}
    button.primary{background:#4654ff}
    button.warn{background:#ff9f1c}
    button.danger{background:#ef476f}
    button:disabled{opacity:.4;cursor:not-allowed}
    .flash{margin-bottom:12px;padding:10px 14px;border-radius:10px}
    .flash.ok{background:rgba(103,211,139,.12);border:1px solid rgba(103,211,139,.35)}
    .flash.err{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.35)}
    code{background:#0b0e1a;padding:2px 6px;border-radius:6px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Luta — <span class="state <?= htmlspecialchars($luta->getSituacao()) ?>"><?= htmlspecialchars($estado->label()) ?></span></h1>

      <?php if ($flash): ?>
        <div class="flash <?= $flash['ok'] ? 'ok' : 'err' ?>"><?= htmlspecialchars($flash['msg']) ?></div>
      <?php endif; ?>

      <div class="grid">
        <div class="box">
          <strong>Lutadores</strong>
          <p class="muted"><?= htmlspecialchars(implode(' × ', $luta->getLutadores())) ?></p>
          <small class="muted">Situação (char): <code><?= htmlspecialchars($luta->getSituacao()) ?></code></small>
        </div>
        <div class="box">
          <strong>Ações</strong>
          <form method="post">
            <button name="action" value="agendar" class="primary" <?= $luta->getSituacao()==='C' ? '' : 'disabled' ?>>Agendar</button>
            <button name="action" value="finalizar" class="warn" <?= $luta->getSituacao()==='A' ? '' : 'disabled' ?>>Finalizar</button>
            <button name="action" value="cancelar" class="danger" <?= $luta->getSituacao()==='A' ? '' : 'disabled' ?>>Cancelar</button>
            <button name="action" value="reset">Resetar exemplo</button>
          </form>
          <p class="muted" style="margin-top:8px">Regras: de <em>Agendada</em> você pode <strong>Finalizar</strong> ou <strong>Cancelar</strong>. De <em>Cancelada</em> é possível <strong>Agendar</strong> novamente. <em>Finalizada</em> não permite novas transições.</p>
        </div>
      </div>

      <p class="muted" style="margin-top:16px">Implementação baseada no diagrama fornecido: classe <code>Luta</code> com atributo <code>situacao: char</code> e relação de composição com <code>EstadoLuta</code> e seus subestados (<code>Agendada</code>, <code>Finalizado</code>, <code>Cancelada</code>).</p>
    </div>
  </div>
</body>
</html>

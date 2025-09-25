<?php
// ── Autoloader ───────────────────────────────────────────────────────────────
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (is_file($file)) require_once $file;
});

// ── Sessão ───────────────────────────────────────────────────────────────────
session_start();

// se existir algo quebrado na sessão, limpa
if (isset($_SESSION['notif']) && !($_SESSION['notif'] instanceof Notificacao)) {
    unset($_SESSION['notif']);
}

// cria o objeto de estado da notificação
if (!isset($_SESSION['notif'])) {
    $_SESSION['notif'] = new Notificacao(true); // começa liberado
}
$notif = $_SESSION['notif'];

$msg = null;
$abrirModal = false;

// ── Handler POST ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'alterar_estado') {
            $estado = $_POST['estado'] ?? 'liberado'; // liberado|bloqueado
            $contexto = new GerenciadorNotificacao(
                $estado === 'bloqueado' ? new BloquearNotificacao()
                                         : new AtivarNotificacao()
            );
            $contexto->processar($notif);
            $_SESSION['notif'] = $notif; // persiste o novo estado
            $msg = $notif->getDisparar()
                ? 'Notificação LIBERADA.'
                : 'Notificação BLOQUEADA.';
        }

        if ($acao === 'disparar') {
            if ($notif->getDisparar()) {
                $abrirModal = true; // front-end abrirá o modal
                $msg = 'Popup disparado com sucesso.';
            } else {
                $msg = 'Popup bloqueado. Altere o seletor para "Liberado".';
            }
        }
    } catch (Throwable $e) {
        $msg = 'Erro: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Strategy • Notificação (popup)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f7fb; }
    .card{ border:0; border-radius:1rem; box-shadow:0 8px 24px rgba(0,0,0,.06); }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Estado da Notificação</h5>

          <form method="post" class="row g-2 align-items-end">
            <input type="hidden" name="acao" value="alterar_estado">
            <div class="col-8">
              <label class="form-label">Selecione o estado</label>
              <select name="estado" class="form-select">
                <option value="liberado"  <?= $notif->getDisparar() ? 'selected' : '' ?>>Liberado</option>
                <option value="bloqueado" <?= !$notif->getDisparar() ? 'selected' : '' ?>>Bloqueado</option>
              </select>
            </div>
            <div class="col-4">
              <button class="btn btn-primary w-100">Aplicar</button>
            </div>
          </form>

          <hr>

          <form method="post">
            <input type="hidden" name="acao" value="disparar">
            <button class="btn btn-success">Disparar notificação (popup)</button>
          </form>

          <?php if ($msg): ?>
            <div class="alert alert-info mt-3 mb-0"><?= htmlspecialchars($msg) ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Explicação (Strategy)</h5>
          <ul class="mb-0">
            <li><code>Notificacao</code> mantém o estado <code>canDisparar</code>.</li>
            <li><code>AtivarNotificacao</code> e <code>BloquearNotificacao</code> implementam <code>NotificacaoGerenciador</code>.</li>
            <li>O <strong>Contexto</strong> <code>GerenciadorNotificacao</code> aplica a estratégia ao estado.</li>
            <li>O botão só abre o popup se <code>getDisparar() === true</code>.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal (popup) -->
<div class="modal fade" id="notifModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notificação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Este é o popup! Ele só aparece quando o estado está <strong>LIBERADO</strong>.
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($abrirModal): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('notifModal'));
    modal.show();
  });
</script>
<?php endif; ?>
</body>
</html>

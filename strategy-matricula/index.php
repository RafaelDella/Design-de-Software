<?php
require_once __DIR__ . '\public/classes/Academia.php';
require_once __DIR__ . '\public/classes/Usuario.php';
require_once __DIR__ . '\public/classes/AtualizacaoCadastro.php';
require_once __DIR__ . '\public/classes/DeletarConta.php';
require_once __DIR__ . '\public/classes/MatricularUsuario.php';
require_once __DIR__ . '\public/classes/GerenciadorCadastro.php';

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php'; // <- pasta classes na raiz
    if (is_file($file)) {
        require_once $file;
    }
});

session_start();

if (isset($_SESSION['usuario']) && !($_SESSION['usuario'] instanceof Usuario)) {
    unset($_SESSION['usuario']);
}

if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = new Usuario(
        id: 1,
        nome: 'Ana',
        email: 'ana@email.com',
        ativo: true,
        academias: []
    );
}

$ACADEMIAS_FIXAS = [
    new Academia(10, 'Academia Central'),
    new Academia(11, 'Academia Norte'),
    new Academia(12, 'Academia Sul'),
];

$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    try {
        if ($acao === 'matricular') {
            $idAcademia = (int)($_POST['academia_id'] ?? 0);
            $academia = null;
            foreach ($ACADEMIAS_FIXAS as $a) {
                if ($a->id === $idAcademia) { $academia = $a; break; }
            }
            if (!$academia) {
                throw new RuntimeException('Selecione uma academia válida.');
            }
            $contexto = new GerenciadorCadastro(new MatricularUsuario($academia));
            $_SESSION['usuario'] = $contexto->processar($_SESSION['usuario']);
            $msg = "Usuário matriculado em {$academia->nome}.";
        } elseif ($acao === 'deletar') {
            $contexto = new GerenciadorCadastro(new DeletarConta());
            $_SESSION['usuario'] = $contexto->processar($_SESSION['usuario']);
            $msg = 'Conta deletada (inativada) e vínculos removidos.';
        } elseif ($acao === 'reativar') {
            $_SESSION['usuario']->ativo = true;
            $msg = 'Conta reativada para fins de teste.';
        }
    } catch (Throwable $e) {
        $msg = 'Erro: ' . $e->getMessage();
    }
}

$usuario = $_SESSION['usuario'];

if (!is_array($usuario->academias)) {
    $usuario->academias = [];
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Strategy • Atualização de Cadastro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f7fb; }
    .card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
    .badge-pill { border-radius: 50rem; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Usuário</h5>
          <p class="mb-1"><strong>ID:</strong> <?= htmlspecialchars((string)$usuario->id) ?></p>
          <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($usuario->nome) ?></p>
          <p class="mb-1"><strong>E-mail:</strong> <?= htmlspecialchars($usuario->email) ?></p>
          <p class="mb-3">
            <strong>Status:</strong>
            <?php if ($usuario->ativo): ?>
              <span class="badge text-bg-success">Ativo</span>
            <?php else: ?>
              <span class="badge text-bg-secondary">Inativo</span>
            <?php endif; ?>
          </p>
          <div>
            <strong>Acadêmias:</strong><br>
            <?php if (count($usuario->academias) === 0): ?>
              <span class="text-muted">Sem vínculos</span>
            <?php else: ?>
              <?php foreach ($usuario->academias as $a): ?>
                <span class="badge text-bg-primary badge-pill me-1 mb-1"><?= htmlspecialchars($a->nome) ?></span>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <?php if ($msg): ?>
        <div class="alert alert-info mt-3 mb-0"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>
    </div>

    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Ações (Strategy)</h5>

          <form method="post" class="row g-3">
            <input type="hidden" name="acao" value="matricular">
            <div class="col-md-8">
              <label class="form-label">Matricular em uma academia</label>
              <select name="academia_id" class="form-select" <?= !$usuario->ativo ? 'disabled' : '' ?>>
                <option value="">Selecione...</option>
                <?php foreach ($ACADEMIAS_FIXAS as $a): ?>
                  <option value="<?= $a->id ?>"><?= htmlspecialchars($a->nome) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button class="btn btn-primary w-100" <?= !$usuario->ativo ? 'disabled' : '' ?>>Matricular</button>
            </div>
          </form>

          <hr>

          <form method="post" class="d-flex gap-2">
            <input type="hidden" name="acao" value="deletar">
            <button class="btn btn-outline-danger" <?= !$usuario->ativo && empty($usuario->academias) ? 'disabled' : '' ?>>
              Deletar conta (Strategy: DeletarConta)
            </button>
          </form>

          <form method="post" class="mt-2">
            <input type="hidden" name="acao" value="reativar">
            <button class="btn btn-outline-secondary">Reativar (para testar)</button>
          </form>

          <p class="text-muted mt-3 mb-0">
            * As ações acima usam o mesmo contrato <code>AtualizacaoCadastro</code> e apenas trocam a estratégia em tempo de execução.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

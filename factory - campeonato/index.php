<?php

$selected = $_POST['tipo'] ?? null;
$instancia = null;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!in_array($selected, ['PROFISSIONAL', 'AMADOR', '4FUN'], true)) {
            throw new InvalidArgumentException('Seleção inválida.');
        }
        // Mapeia string do POST para enum
        $enum = match ($selected) {
            'PROFISSIONAL' => CampeonatoCat::PROFISSIONAL,
            'AMADOR'       => CampeonatoCat::AMADOR,
            '4FUN'         => CampeonatoCat::FOUR_FUN,
        };
        $instancia = CampeonatoFactory::setCampeonato($enum);
    } catch (Throwable $e) {
        $erro = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Factory — Campeonato</title>
  <style>
    :root { --bg:#0f1221; --card:#171a2e; --muted:#aab1c7; }
    *{box-sizing:border-box;font-family:system-ui,Inter,Arial}
    body{margin:0;background:linear-gradient(180deg,#0b0e1a,#0f1221);color:#eef1ff;min-height:100vh;display:flex;align-items:center;justify-content:center}
    .wrap{width:min(860px,92vw)}
    .card{background:var(--card);border-radius:18px;padding:24px;box-shadow:0 12px 30px rgba(0,0,0,.35)}
    h1{margin:.2rem 0 1rem;font-weight:800}
    .muted{color:var(--muted)}
    form{display:flex;gap:10px;flex-wrap:wrap;margin-top:6px}
    select,button{border:0;border-radius:10px;padding:10px 14px;font-weight:600}
    select{background:#0d1130;color:#fff}
    button{background:#4654ff;color:#fff;cursor:pointer}
    .box{margin-top:16px;background:#111632;border:1px solid #242842;border-radius:14px;padding:14px}
    .err{background:rgba(255,107,107,.12);border:1px solid rgba(255,107,107,.35);padding:10px 14px;border-radius:10px}
    code{background:#0b0e1a;padding:2px 6px;border-radius:6px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Campeonato — <span class="muted">Factory</span></h1>

      <form method="post">
        <label for="tipo">Tipo de campeonato:</label>
        <select id="tipo" name="tipo">
          <option value="PROFISSIONAL" <?= $selected==='PROFISSIONAL'?'selected':'' ?>>Profissional</option>
          <option value="AMADOR"       <?= $selected==='AMADOR'?'selected':'' ?>>Amador</option>
          <option value="4FUN"         <?= $selected==='4FUN'?'selected':'' ?>>4Fun</option>
        </select>
        <button type="submit">Criar com Factory</button>
      </form>

      <?php if ($erro): ?>
        <p class="err"><?= htmlspecialchars($erro) ?></p>
      <?php endif; ?>

      <?php if ($instancia instanceof Campeonato): ?>
        <div class="box">
          <strong>Instância criada:</strong>
          <p>Classe concreta: <code><?= htmlspecialchars($instancia::class) ?></code></p>
          <p>Enum (<code>get()</code>): <code><?= htmlspecialchars($instancia->get()->value) ?></code></p>
          <p>Descrição: <?= htmlspecialchars($instancia->descricao()) ?></p>
        </div>
      <?php endif; ?>

      <p class="muted" style="margin-top:10px">
        Diagrama respeitado: <code>CampeonatoFactory::setCampeonato(cat: CampeonatoCat): Campeonato</code>
        → retorna <code>Profissional</code>, <code>Amador</code> ou <code>4Fun</code>. A base <code>Campeonato</code> expõe
        <code>get()</code> e <code>set(CampeonatoCat)</code>.
      </p>
    </div>
  </div>
</body>
</html>

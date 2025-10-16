<?php

<?php
/**
 * index.php — Factory Pattern conforme diagrama:
 *  - enum CategoriaFaixa { INFANTIL, JUVENIL, ADULTO }
 *  - Classe base Categoria (get/set da faixa)
 *  - Concretas: Infantil, Juvenil, Adulto
 *  - CategoriaFactory::setCategoria(CategoriaFaixa): Categoria
 *  - Frontend: dropdown para criar a instância via Factory
 */

declare(strict_types=1);

/* =========================
 * ENUM conforme diagrama
 * ========================= */
enum CategoriaFaixa: string
{
    case INFANTIL = 'INFANTIL';
    case JUVENIL  = 'JUVENIL';
    case ADULTO   = 'ADULTO';
}

/* =========================
 * Classe base: Categoria
 * (+ get():CategoriaFaixa, + set(cat:CategoriaFaixa):void)
 * ========================= */
abstract class Categoria
{
    protected CategoriaFaixa $faixa;

    // UML indica: + Categoria(cat:int):CategoriaFaixa (interpretação: construtor recebe a faixa e define o atributo)
    public function __construct(CategoriaFaixa $cat)
    {
        $this->faixa = $cat;
    }

    public function get(): CategoriaFaixa
    {
        return $this->faixa;
    }

    public function set(CategoriaFaixa $cat): void
    {
        $this->faixa = $cat;
    }

    // Auxiliar para UI
    abstract public function descricao(): string;
}

/* =========================
 * Concretas: Infantil, Juvenil, Adulto
 * (incluem os métodos privados *Cat() como no diagrama)
 * ========================= */
final class Infantil extends Categoria
{
    public function __construct()
    {
        parent::__construct(CategoriaFaixa::INFANTIL);
        $this->InfantilCat();
    }

    // -InfantilCat():void (privado, apenas para refletir o diagrama)
    private function InfantilCat(): void { /* inicializações específicas, se houver */ }

    public function descricao(): string
    {
        return 'Faixa INFANTIL — até 12 anos.';
    }
}

final class Juvenil extends Categoria
{
    public function __construct()
    {
        parent::__construct(CategoriaFaixa::JUVENIL);
        $this->JuvenilCat();
    }

    // -JuvenilCat():void
    private function JuvenilCat(): void { /* inicializações específicas, se houver */ }

    public function descricao(): string
    {
        return 'Faixa JUVENIL — 13 a 17 anos.';
    }
}

final class Adulto extends Categoria
{
    public function __construct()
    {
        parent::__construct(CategoriaFaixa::ADULTO);
        $this->AdultoCat();
    }

    // -AdultoCat():void
    private function AdultoCat(): void { /* inicializações específicas, se houver */ }

    public function descricao(): string
    {
        return 'Faixa ADULTO — 18+ anos.';
    }
}

/* =========================
 * Factory: CategoriaFactory
 * (+ setCategoria(cat:CategoriaFaixa):Categoria)
 * ========================= */
final class CategoriaFactory
{
    public static function setCategoria(CategoriaFaixa $cat): Categoria
    {
        return match ($cat) {
            CategoriaFaixa::INFANTIL => new Infantil(),
            CategoriaFaixa::JUVENIL  => new Juvenil(),
            CategoriaFaixa::ADULTO   => new Adulto(),
        };
    }
}

/* =========================
 * Frontend simples (POST)
 * ========================= */
$selected = $_POST['faixa'] ?? null;
$instance = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!in_array($selected, ['INFANTIL', 'JUVENIL', 'ADULTO'], true)) {
            throw new InvalidArgumentException('Seleção inválida.');
        }
        $enum = CategoriaFaixa::from($selected);
        $instance = CategoriaFactory::setCategoria($enum);
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Factory — Categoria</title>
  <style>
    :root { --bg:#0f1221; --card:#171a2e; --muted:#aab1c7; }
    *{box-sizing:border-box;font-family:system-ui,Inter,Arial}
    body{margin:0;background:linear-gradient(180deg,#0b0e1a,#0f1221);color:#eef1ff;min-height:100vh;display:flex;align-items:center;justify-content:center}
    .wrap{width:min(820px,92vw)}
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
      <h1>Categoria — <span class="muted">Factory</span></h1>

      <form method="post">
        <label for="faixa">Escolha a faixa:</label>
        <select id="faixa" name="faixa">
          <option value="INFANTIL" <?= $selected==='INFANTIL'?'selected':'' ?>>INFANTIL</option>
          <option value="JUVENIL"  <?= $selected==='JUVENIL'?'selected':'' ?>>JUVENIL</option>
          <option value="ADULTO"   <?= $selected==='ADULTO'?'selected':'' ?>>ADULTO</option>
        </select>
        <button type="submit">Criar com Factory</button>
      </form>

      <?php if ($error): ?>
        <p class="err"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <?php if ($instance instanceof Categoria): ?>
        <div class="box">
          <strong>Instância criada:</strong>
          <p>Classe concreta: <code><?= htmlspecialchars($instance::class) ?></code></p>
          <p>Enum (<code>get()</code>): <code><?= htmlspecialchars($instance->get()->value) ?></code></p>
          <p>Descrição: <?= htmlspecialchars($instance->descricao()) ?></p>
        </div>
      <?php endif; ?>

      <p class="muted" style="margin-top:10px">
        Diagrama respeitado: <code>CategoriaFactory::setCategoria(cat: CategoriaFaixa): Categoria</code>
        → retorna <code>Infantil</code>, <code>Juvenil</code> ou <code>Adulto</code>. A base <code>Categoria</code> possui
        <code>get()</code> e <code>set(CategoriaFaixa)</code>.
      </p>
    </div>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Memento - Academia</title>
  <style>
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background: #0f172a;
      color: #e2e8f0;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      background: #1e293b;
      border-radius: 12px;
      padding: 24px;
      width: 420px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    h1 {
      text-align: center;
      font-size: 1.5rem;
      color: #38bdf8;
      margin-bottom: 16px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    label {
      font-weight: 600;
      font-size: 0.9rem;
      color: #cbd5e1;
    }

    input {
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #334155;
      background-color: #0f172a;
      color: #f8fafc;
      outline: none;
      transition: border-color 0.2s ease;
    }

    input:focus {
      border-color: #38bdf8;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 8px;
    }

    button {
      flex: 1;
      margin: 4px;
      padding: 10px 12px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
      color: #0f172a;
    }

    .btn-save {
      background-color: #22c55e;
    }

    .btn-restore {
      background-color: #fbbf24;
    }

    .btn-clear {
      background-color: #ef4444;
      color: #fff;
    }

    button:hover {
      transform: translateY(-2px);
      filter: brightness(1.1);
    }

    .history {
      margin-top: 24px;
      border-top: 1px solid #334155;
      padding-top: 16px;
    }

    .history h2 {
      color: #38bdf8;
      font-size: 1rem;
      margin-bottom: 8px;
    }

    .snapshot {
      background: #0f172a;
      border: 1px solid #334155;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .snapshot small {
      color: #94a3b8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Academia - Memento</h1>
    <form method="POST" action="academia_controller.php">
      <div>
        <label for="aluno">Aluno:</label>
        <input type="text" id="aluno" name="aluno" placeholder="Nome do aluno" required>
      </div>

      <div>
        <label for="professor">Professor:</label>
        <input type="text" id="professor" name="professor" placeholder="Nome do professor" required>
      </div>

      <div class="buttons">
        <button class="btn-save" type="submit" name="action" value="salvar">Salvar</button>
        <button class="btn-restore" type="submit" name="action" value="restaurar">Restaurar</button>
        <button class="btn-clear" type="submit" name="action" value="limpar">Limpar</button>
      </div>
    </form>

    <div class="history">
      <h2>Histórico de Estados</h2>

      <div class="snapshot">
        <div>
          <strong>Aluno:</strong> Ana <br>
          <strong>Professor:</strong> Carlos
        </div>
        <small>Salvo em 06/10/2025</small>
      </div>

      <div class="snapshot">
        <div>
          <strong>Aluno:</strong> Bruno <br>
          <strong>Professor:</strong> Denise
        </div>
        <small>Salvo em 05/10/2025</small>
      </div>
    </div>
  </div>
</body>
</html>

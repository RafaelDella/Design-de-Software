<?php

final class Carteira {
    private int $usuarioID;
    private float $saldo;
    private array $transacoes;

    public function __construct(int $usuarioID, float $saldo = 0.0, array $transacoes = []) {
        $this->usuarioID  = $usuarioID;
        $this->saldo      = $saldo;
        $this->transacoes = $transacoes;
    }

    public function consultarSaldo(): float {
        return $this->saldo;
    }

    public function consultarExtrato(): string {
        if (!$this->transacoes) return "Sem movimentações ainda.";
        return implode("\n", $this->transacoes);
    }

    public function depositar(float $valor): void {
        if ($valor <= 0) throw new InvalidArgumentException("Valor inválido para depósito.");
        $this->saldo += $valor;
        $this->transacoes[] = date('d/m/Y H:i') . " • Depósito: + R$ " . number_format($valor, 2, ',', '.');
    }

    public function sacar(float $valor): void {
        if ($valor <= 0) throw new InvalidArgumentException("Valor inválido para saque.");
        if ($valor > $this->saldo) throw new RuntimeException("Saldo insuficiente.");
        $this->saldo -= $valor;
        $this->transacoes[] = date('d/m/Y H:i') . " • Saque: - R$ " . number_format($valor, 2, ',', '.');
    }

    public function salvarExtrato(): Memento {
        return new Memento($this->usuarioID, $this->saldo, $this->transacoes);
    }

    public function restaurar(Memento $m): void {
        if ($m->getUsuarioID() !== $this->usuarioID) {
            throw new LogicException("Memento de outro usuário.");
        }
        $this->saldo      = $m->getSaldo();
        $this->transacoes = $m->getTransacoes();
    }

    public function toArray(): array {
        return [
            'usuarioID'  => $this->usuarioID,
            'saldo'      => $this->saldo,
            'transacoes' => $this->transacoes,
        ];
    }
    public static function fromArray(array $raw): self {
        return new self($raw['usuarioID'], (float)$raw['saldo'], $raw['transacoes']);
    }
}

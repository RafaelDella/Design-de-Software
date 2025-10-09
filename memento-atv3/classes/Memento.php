<?php

final class Memento {
    private int $usuarioID;
    private float $saldo;
    private array $transacoes;

    public function __construct(int $usuarioID, float $saldo, array $transacoes) {
        $this->usuarioID  = $usuarioID;
        $this->saldo      = $saldo;
        $this->transacoes = $transacoes;
    }
    public function getUsuarioID(): int { return $this->usuarioID; }
    public function getSaldo(): float { return $this->saldo; }
    public function getTransacoes(): array { return $this->transacoes; }
}
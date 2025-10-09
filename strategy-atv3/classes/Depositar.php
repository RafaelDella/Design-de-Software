<?php

class Depositar implements DadosConta {
    private $saldo;

    public function __construct(float $saldoInicial) {
        $this->saldo = $saldoInicial;
    }

    public function depositar(float $valor): void {
        if ($valor <= 0) {
            echo "<p class='error'>Valor inválido para depósito.</p>";
            return;
        }
        $this->saldo += $valor;
        echo "<p class='success'>Depósito de R$ $valor realizado com sucesso!</p>";
    }

    public function consultar(): float {
        return $this->saldo;
    }
}
<?php

class Sacar implements DadosConta {
    private $saldo;

    public function __construct(float $saldoInicial) {
        $this->saldo = $saldoInicial;
    }

    public function sacar(float $valor): void {
        if ($valor <= 0) {
            echo "<p class='error'>Valor inválido para saque.</p>";
            return;
        }
        if ($valor > $this->saldo) {
            echo "<p class='error'>Saldo insuficiente.</p>";
            return;
        }
        $this->saldo -= $valor;
        echo "<p class='success'>Saque de R$ $valor realizado com sucesso!</p>";
    }

    public function consultar(): float {
        return $this->saldo;
    }
}
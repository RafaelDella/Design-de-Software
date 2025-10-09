<?php

class Consultar implements DadosConta {
    private $saldo;

    public function __construct(float $saldo) {
        $this->saldo = $saldo;
    }

    public function consultar(): float {
        return $this->saldo;
    }
}
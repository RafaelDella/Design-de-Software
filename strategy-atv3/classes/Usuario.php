<?php

class Usuario {
    private int $id;
    private string $nome;
    private DadosConta $dadosConta;

    public function __construct(int $id, string $nome, DadosConta $dadosConta) {
        $this->id = $id;
        $this->nome = $nome;
        $this->dadosConta = $dadosConta;
    }

    public function infoCarteira(): int {
        return rand(1, 100);
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getConta(): DadosConta {
        return $this->dadosConta;
    }
}
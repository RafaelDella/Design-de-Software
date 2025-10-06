<?php

abstract class Usuario
{
    protected string $nome;
    protected string $cpf;
    protected string $email;
    protected string $sexo;

    public function __construct(string $nome, string $cpf, string $email, string $sexo)
    {
        $this->nome  = $nome;
        $this->cpf   = $cpf;
        $this->email = $email;
        $this->sexo  = $sexo;
    }

    public function getNome(): string { return $this->nome; }
    public function getCpf(): string { return $this->cpf; }
    public function getEmail(): string { return $this->email; }
    public function getSexo(): string { return $this->sexo; }
}
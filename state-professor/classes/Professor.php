<?php

class Professor
{
    private string $nome;
    private string $situacao;
    private EstadoProfessor $estado;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
        $this->setEstado(new Atuando($this));
    }

    public function setEstado(EstadoProfessor $estado): void
    {
        $this->estado = $estado;
        $this->situacao = $estado->getSituacao();
    }

    public function getEstado(): EstadoProfessor { return $this->estado; }
    public function getSituacao(): string { return $this->situacao; }
    public function getNome(): string { return $this->nome; }

    public function atuar(): void { $this->estado->atuar(); }
    public function tirarFerias(): void { $this->estado->tirarFerias(); }
    public function demitir(): void { $this->estado->demitir(); }
}

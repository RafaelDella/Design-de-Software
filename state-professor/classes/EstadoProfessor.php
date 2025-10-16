<?php

abstract class EstadoProfessor
{
    protected Professor $professor;

    public function __construct(Professor $professor)
    {
        $this->professor = $professor;
    }

    abstract public function getSituacao(): string; // A, F, D

    public function atuar(): void { throw new RuntimeException('Transição inválida: não é possível atuar neste estado.'); }
    public function tirarFerias(): void { throw new RuntimeException('Transição inválida: não é possível tirar férias neste estado.'); }
    public function demitir(): void { throw new RuntimeException('Transição inválida: não é possível demitir neste estado.'); }

    public function label(): string
    {
        return match ($this->getSituacao()) {
            'A' => 'Atuando',
            'F' => 'Férias',
            'D' => 'Demitido',
            default => 'Desconhecida'
        };
    }
}

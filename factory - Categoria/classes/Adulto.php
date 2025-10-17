<?php
final class Adulto extends Categoria
{
    public function __construct()
    {
        parent::__construct(CategoriaFaixa::ADULTO);
        $this->AdultoCat();
    }

    private function AdultoCat(): void {}

    public function descricao(): string
    {
        return 'Faixa ADULTO — 18+ anos.';
    }
}
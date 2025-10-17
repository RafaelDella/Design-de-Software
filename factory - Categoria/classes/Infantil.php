<?php
final class Infantil extends Categoria
{
    public function __construct()
    {
        parent::__construct(CategoriaFaixa::INFANTIL);
        $this->InfantilCat();
    }

    private function InfantilCat(): void {}

    public function descricao(): string
    {
        return 'Faixa INFANTIL — até 12 anos.';
    }
}

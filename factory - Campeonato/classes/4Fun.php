<?php

final class FourFun extends Campeonato
{
    public function __construct()
    {
        parent::__construct(CampeonatoCat::FOUR_FUN);
        $this->FourFunCat();
    }

    private function FourFunCat(): void
    {}

    public function descricao(): string
    {
        return 'Campeonato 4Fun — formato casual/diversão, regras simplificadas.';
    }
}

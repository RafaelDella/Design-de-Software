<?php

final class Profissional extends Campeonato
{
    public function __construct()
    {
        parent::__construct(CampeonatoCat::PROFISSIONAL);
        $this->ProfissionalCat();
    }

    private function ProfissionalCat(): void
    {}

    public function descricao(): string
    {
        return 'Campeonato PROFISSIONAL — exige registro de atletas e regulamento oficial.';
    }
}
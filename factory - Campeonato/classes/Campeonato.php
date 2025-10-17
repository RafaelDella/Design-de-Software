<?php

abstract class Campeonato
{
    protected CampeonatoCat $tipo;

    public function __construct(CampeonatoCat $cat)
    {
        $this->tipo = $cat;
    }

    public function get(): CampeonatoCat
    {
        return $this->tipo;
    }

    public function set(CampeonatoCat $cat): void
    {
        $this->tipo = $cat;
    }

    abstract public function descricao(): string;
}

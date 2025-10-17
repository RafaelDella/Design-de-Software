<?php
abstract class Categoria
{
    protected CategoriaFaixa $faixa;

    public function __construct(CategoriaFaixa $cat)
    {
        $this->faixa = $cat;
    }

    public function get(): CategoriaFaixa
    {
        return $this->faixa;
    }

    public function set(CategoriaFaixa $cat): void
    {
        $this->faixa = $cat;
    }

    abstract public function descricao(): string;
}

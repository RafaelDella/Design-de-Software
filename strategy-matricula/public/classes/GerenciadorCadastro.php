<?php

final class GerenciadorCadastro
{
    public function __construct(private AtualizacaoCadastro $estrategia) {}

    public function setEstrategia(AtualizacaoCadastro $estrategia): void
    {
        $this->estrategia = $estrategia;
    }

    public function processar(Usuario $usuario): Usuario
    {
        return $this->estrategia->editarCadastro($usuario);
    }
}
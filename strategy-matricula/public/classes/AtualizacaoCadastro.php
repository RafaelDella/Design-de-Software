<?php

interface AtualizacaoCadastro
{
    public function editarCadastro(Usuario $usuario): Usuario;
}
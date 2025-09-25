<?php
final class DeletarConta implements AtualizacaoCadastro
{
    public function editarCadastro(Usuario $usuario): Usuario
    {
        $usuario->ativo = false;
        $usuario->removerTodasAcademias();
        return $usuario;
    }

    // opcional, só pra refletir o diagrama
    public function deletarConta(Usuario $cadastro): void
    {
        $this->editarCadastro($cadastro);
    }
}

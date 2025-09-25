<?php
final class MatricularUsuario implements AtualizacaoCadastro
{
    public function __construct(private Academia $academiaAlvo) {}

    public function editarCadastro(Usuario $usuario): Usuario
    {
        if (!$usuario->ativo) {
            throw new RuntimeException('Usuário inativo — não é possível matricular.');
        }
        $usuario->adicionarAcademia($this->academiaAlvo);
        return $usuario;
    }

    // opcional, só pra refletir o diagrama
    public function matricularAcademia(Usuario $u, Academia $a): void
    {
        $this->editarCadastro($u);
    }
}

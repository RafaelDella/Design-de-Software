<?php
class MementoAcademia implements Memento2
{
    private string $aluno;
    private string $professor;

    public function __construct(string $aluno, string $professor)
    {
        $this->aluno     = $aluno;
        $this->professor = $professor;
    }

    public function getAluno(): string     { return $this->aluno; }
    public function getProfessor(): string { return $this->professor; }
}

<?php
class Academia
{
    private string $aluno;
    private string $professor;

    private HistoricoAcademia $historico;

    public function __construct(string $aluno, string $professor)
    {
        $this->aluno     = $aluno;
        $this->professor = $professor;
        $this->historico = new HistoricoAcademia();
    }

    public function getAluno(): string { return $this->aluno; }
    public function setAluno(string $aluno): void { $this->aluno = $aluno; }

    public function getProfessor(): string { return $this->professor; }
    public function setProfessor(string $professor): void { $this->professor = $professor; }

    public function Salvar(): void
    {
        $this->historico->push(new MementoAcademia($this->aluno, $this->professor));
    }

    public function Restaurar(): ?Memento2
    {
        $m = $this->historico->pop();
        if ($m instanceof Memento2) {
            $this->aluno     = $m->getAluno();
            $this->professor = $m->getProfessor();
        }
        return $m;
    }

    public function historicoCount(): int
    {
        return $this->historico->count();
    }
}

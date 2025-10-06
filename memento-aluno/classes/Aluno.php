<?php
 class Aluno extends Usuario
{
    private string $faixa;
    private float $peso;
    private float $altura;

    private HistoricoAluno $historico;

    public function __construct(
        string $nome,
        string $cpf,
        string $email,
        string $sexo,
        string $faixa,
        float $peso,
        float $altura
    ) {
        parent::__construct($nome, $cpf, $email, $sexo);
        $this->faixa  = $faixa;
        $this->peso   = $peso;
        $this->altura = $altura;

        $this->historico = new HistoricoAluno();
    }

    public function getFaixa(): string { return $this->faixa; }
    public function setFaixa(string $faixa): void { $this->faixa = $faixa; }

    public function getPeso(): float { return $this->peso; }
    public function setPeso(float $peso): void { $this->peso = $peso; }

    public function getAltura(): float { return $this->altura; }
    public function setAltura(float $altura): void { $this->altura = $altura; }

    public function Salvar(): void
    {
        $m = new MementoAluno($this->faixa, $this->peso, $this->altura);
        $this->historico->push($m);
    }

    public function Restaurar(): ?Memento
    {
        $m = $this->historico->pop();
        if ($m instanceof Memento) {
            $this->faixa  = $m->getFaixa();
            $this->peso   = $m->getPeso();
            $this->altura = $m->getAltura();
        }
        return $m;
    }

    public function Deletar(): void
    {
        $this->historico->clear();
    }
}
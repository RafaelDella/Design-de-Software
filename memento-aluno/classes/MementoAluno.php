class MementoAluno implements Memento
{
    private string $faixa;
    private float $peso;
    private float $altura;

    public function __construct(string $faixa, float $peso, float $altura)
    {
        $this->faixa  = $faixa;
        $this->peso   = $peso;
        $this->altura = $altura;
    }

    public function getFaixa(): string { return $this->faixa; }
    public function getPeso(): float { return $this->peso; }
    public function getAltura(): float { return $this->altura; }
}

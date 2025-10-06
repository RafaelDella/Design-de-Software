class HistoricoAluno
{
    private array $pilha = [];

    public function push(Memento $m): void
    {
        $this->pilha[] = $m;
    }

    public function pop(): ?Memento
    {
        return array_pop($this->pilha) ?? null;
    }

    public function peek(): ?Memento
    {
        return $this->pilha[count($this->pilha) - 1] ?? null;
    }

    public function clear(): void
    {
        $this->pilha = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->pilha);
    }
}

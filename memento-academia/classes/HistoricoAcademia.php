<?php
class HistoricoAcademia
{
    /** @var Memento2[] */
    private array $stack = [];

    public function push(Memento2 $m): void { $this->stack[] = $m; }

    public function pop(): ?Memento2
    {
        return array_pop($this->stack) ?? null;
    }

    public function clear(): void { $this->stack = []; }

    public function count(): int { return count($this->stack); }
}

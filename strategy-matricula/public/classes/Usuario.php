<?php
final class Usuario
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $email,
        public bool $ativo = true,
        /** @var Academia[] */
        public array $academias = []   // <- default
    ) {}

    public function adicionarAcademia(Academia $academia): void
    {
        foreach ($this->academias as $a) {
            if ($a->id === $academia->id) return;
        }
        $this->academias[] = $academia;
    }

    public function removerTodasAcademias(): void
    {
        $this->academias = [];
    }
}

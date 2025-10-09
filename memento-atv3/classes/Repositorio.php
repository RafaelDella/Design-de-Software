<?php

final class Repositorio {
    private array $dadosCarteira = [];

    public function salvaEstado(Memento $m): void {
        $this->dadosCarteira[] = $m;
    }

    public function desfazer(): void {
        if (!empty($this->dadosCarteira)) array_pop($this->dadosCarteira);
    }

    public function topo(): ?Memento {
        if (empty($this->dadosCarteira)) return null;
        return $this->dadosCarteira[count($this->dadosCarteira) - 1];
    }

    public function toArray(): array {
        return array_map(
            fn(Memento $m) => [
                'usuarioID'  => $m->getUsuarioID(),
                'saldo'      => $m->getSaldo(),
                'transacoes' => $m->getTransacoes(),
            ],
            $this->dadosCarteira
        );
    }
    public static function fromArray(array $raw): self {
        $r = new self();
        foreach ($raw as $m) {
            $r->salvaEstado(new Memento($m['usuarioID'], (float)$m['saldo'], $m['transacoes']));
        }
        return $r;
    }
}
<?php

class Luta
{
    /** @var string Código do estado atual (char) */
    private string $situacao;

    /** @var string[] */
    private array $lutadores;

    private EstadoLuta $estado;

    public function __construct(array $lutadores)
    {
        $this->lutadores = $lutadores;
        $this->setEstado(new Agendada($this));
    }

    public function setEstado(EstadoLuta $estado): void
    {
        $this->estado = $estado;
        $this->situacao = $estado->getSituacao();
    }

    public function getEstado(): EstadoLuta { return $this->estado; }

    public function agendar(): void { $this->estado->agendar(); }
    public function finalizar(): void { $this->estado->finalizar(); }
    public function cancelar(): void { $this->estado->cancelar(); }

    public function getSituacao(): string { return $this->situacao; }
    public function getLutadores(): array { return $this->lutadores; }
}

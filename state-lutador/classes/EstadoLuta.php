<?php

abstract class EstadoLuta
{
    protected Luta $luta;

    public function __construct(Luta $luta)
    {
        $this->luta = $luta;
    }

    abstract public function getSituacao(): string; 

    public function agendar(): void { throw new RuntimeException('Transição inválida: não é possível agendar neste estado.'); }
    public function finalizar(): void { throw new RuntimeException('Transição inválida: não é possível finalizar neste estado.'); }
    public function cancelar(): void { throw new RuntimeException('Transição inválida: não é possível cancelar neste estado.'); }

    public function label(): string
    {
        return match ($this->getSituacao()) {
            'A' => 'Agendada',
            'F' => 'Finalizada',
            'C' => 'Cancelada',
            default => 'Desconhecida'
        };
    }
}
<?php

final class Cancelada extends EstadoLuta
{
    private int $cancelada = 1; // do diagrama

    public function getSituacao(): string { return 'C'; }
    public function agendar(): void { $this->luta->setEstado(new Agendada($this->luta)); }
}

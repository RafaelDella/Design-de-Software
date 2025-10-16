<?php

final class Agendada extends EstadoLuta
{
    private int $agendado = 1; // do diagrama

    public function getSituacao(): string { return 'A'; }
    public function finalizar(): void { $this->luta->setEstado(new Finalizado($this->luta)); }
    public function cancelar(): void { $this->luta->setEstado(new Cancelada($this->luta)); }
}


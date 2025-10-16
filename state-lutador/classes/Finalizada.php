<?php

final class Finalizado extends EstadoLuta
{
    private int $finalizado = 1; // do diagrama

    public function getSituacao(): string { return 'F'; }
}


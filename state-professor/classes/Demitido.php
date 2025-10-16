<?php

final class Demitido extends EstadoProfessor
{
    private int $demitido = 1;
    public function getSituacao(): string { return 'D'; }
}

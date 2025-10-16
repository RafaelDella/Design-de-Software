<?php

final class Atuando extends EstadoProfessor
{
    private int $atuando = 1;
    public function getSituacao(): string { return 'A'; }
    public function tirarFerias(): void { $this->professor->setEstado(new Ferias($this->professor)); }
    public function demitir(): void { $this->professor->setEstado(new Demitido($this->professor)); }
}

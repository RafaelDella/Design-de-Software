<?php

final class Ferias extends EstadoProfessor
{
    private int $ferias = 1;
    public function getSituacao(): string { return 'F'; }
    public function atuar(): void { $this->professor->setEstado(new Atuando($this->professor)); }
    public function demitir(): void { $this->professor->setEstado(new Demitido($this->professor)); }
}

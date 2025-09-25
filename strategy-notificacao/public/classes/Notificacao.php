<?php
final class Notificacao
{
    public function __construct(private bool $canDisparar = true) {}

    public function setDisparar(bool $valor): void
    {
        $this->canDisparar = $valor;
    }

    public function getDisparar(): bool
    {
        return $this->canDisparar;
    }
}

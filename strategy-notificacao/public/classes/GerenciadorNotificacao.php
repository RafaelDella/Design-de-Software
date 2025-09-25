<?php
final class GerenciadorNotificacao
{
    public function __construct(private NotificacaoGerenciador $estrategia) {}

    public function setEstrategia(NotificacaoGerenciador $e): void
    {
        $this->estrategia = $e;
    }

    public function processar(Notificacao $n): void
    {
        $this->estrategia->notificacao($n);
    }
}

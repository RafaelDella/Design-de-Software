<?php
final class AtivarNotificacao implements NotificacaoGerenciador
{
    public function notificacao(Notificacao $n): void
    {
        $n->setDisparar(true);
    }

    // nome opcional para refletir o diagrama
    public function dispararNotificacao(Notificacao $n): void
    {
        $this->notificacao($n);
    }
}

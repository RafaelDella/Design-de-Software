<?php
final class BloquearNotificacao implements NotificacaoGerenciador
{
    public function notificacao(Notificacao $n): void
    {
        $n->setDisparar(false);
    }

    // nome opcional para refletir o diagrama
    public function desativarNotificacao(Notificacao $n): void
    {
        $this->notificacao($n);
    }
}

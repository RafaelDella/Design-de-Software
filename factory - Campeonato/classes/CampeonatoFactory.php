<?php
final class CampeonatoFactory
{
    public static function setCampeonato(CampeonatoCat $cat): Campeonato
    {
        return match ($cat) {
            CampeonatoCat::PROFISSIONAL => new Profissional(),
            CampeonatoCat::AMADOR       => new Amador(),
            CampeonatoCat::FOUR_FUN     => new FourFun(),
        };
    }
}

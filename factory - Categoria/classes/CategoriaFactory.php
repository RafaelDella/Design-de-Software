<?php
final class CategoriaFactory
{
    public static function setCategoria(CategoriaFaixa $cat): Categoria
    {
        return match ($cat) {
            CategoriaFaixa::INFANTIL => new Infantil(),
            CategoriaFaixa::JUVENIL  => new Juvenil(),
            CategoriaFaixa::ADULTO   => new Adulto(),
        };
    }
}

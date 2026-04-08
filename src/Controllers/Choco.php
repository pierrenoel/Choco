<?php 

namespace Cariboo\Choco\Controllers;

class Choco 
{
    public static function capitalize(string $text)
    {
        return \ucfirst($text);
    }

    public static function lower(string $text)
    {
        return \strtolower($text);
    }

    public static function slug(string $text)
    {
        return implode("-", explode(" ",$text));
    }
}
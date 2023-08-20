<?php

namespace App\Helpers;

use Symfony\Component\String\Slugger\AsciiSlugger;

trait SlugifyTrait
{
    public function slugify(string $string): string
    {
        return (string) (new AsciiSlugger())->slug($string)->lower();
    }
}

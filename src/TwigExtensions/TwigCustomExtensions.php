<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigCustomExtensions extends AbstractExtension 
{

    public function getFilters()
    {
        return [
            new TwigFilter("defaultImage", [$this, "defaultImage"])
        ];
    }

    public function defaultImage(string $path) : string
    {

        echo "im used";

        if(strlen(trim($path)) === 0){
            return "assets/images/reddit-logo-2436.png";
        } else {
            return trim($path);
        }

    }

}
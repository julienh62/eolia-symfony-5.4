<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension {
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }
    public function amount($value){
     //dd($value);
     //14500.50
      $finalValue = $value / 100 ;
     // 145.50
      $finalValue = number_format($finalValue, 2, ',', ' ');
      // 145,50

      return $finalValue . ' €';




    }
}


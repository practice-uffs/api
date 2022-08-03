<?php

namespace App\Services;

class RuScraping
{
    /* 
        Como usar:
        use App\Services\RuScraping;
        .
        .
        . 
            $scraper = new RuScraping();
            $menu = $scraper->getMenuByWeekDay('chapeco','seg');
    */
    protected $scraper;

    public function __construct()
    {   
        
        $this->scraper = new \CCUFFS\Scrap\UniversityRestaurantUFFS();
        
    }

    // Recebe o campus {cerro-largo, chapeco, erechim, laranjeiras-do-sul, passo-fundo, realeza}
    public function getMenuByCampus($campus)
    {   
        $menu = $this->scraper->getMenuByCampus($this->scraper->campus[$campus]);
        return $menu;
    }

    // Recebe o campus {cerro-largo, chapeco, erechim, laranjeiras-do-sul, passo-fundo, realeza}
    // e uma data no tipo dd/mm/aaaa
    public function getMenuByDate($campus, $date)
    {   
        $menu = $this->scraper->getMenuByDate($this->scraper->campus[$campus], $date);
        return $menu;
    }

    // Recebe o campus {cerro-largo, chapeco, erechim, laranjeiras-do-sul, passo-fundo, realeza} 
    // e um dia da semana: {seg,ter,qua,qui,sex,sab,dom}
    public function getMenuByWeekDay($campus, $weekday)
    {   
        $menu = $this->scraper->getMenuByWeekDay($this->scraper->campus[$campus], $weekday);
        return $menu;
    }
}


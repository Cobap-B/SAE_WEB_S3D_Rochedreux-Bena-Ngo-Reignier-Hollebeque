<?php
namespace NRV\Event;

class Party extends Event{



    private int $place;
    private array $shows;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, int $place, array $shows = []){
        parent::__construct($id, $tname, $dateDebut, $dateFin);
        $this->place = $place;
        $this->shows = $shows;
    } 

   

}
<?php
namespace NRV\Event;

class Party extends Event{



    private int $place;
    private array $shows;
    private int $tarif;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, int $place, int $tarif, array $shows = []){
        parent::__construct($id, $tname, $dateDebut, $dateFin);
        $this->place = $place;
        $this->shows = $shows;
        $this->tarif = $tarif;
    } 

   

}
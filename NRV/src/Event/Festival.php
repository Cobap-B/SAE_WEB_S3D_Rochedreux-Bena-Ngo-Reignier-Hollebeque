<?php

namespace NRV\Event;


class Festival extends Event{

    private array $partys;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, array $partys = []){
        parent::__construct($id, $name, $dateDebut, $dateFin);
        $this->partys = $partys;
    }

    
}
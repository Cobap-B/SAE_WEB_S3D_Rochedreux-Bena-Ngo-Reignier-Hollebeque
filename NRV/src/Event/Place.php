<?php

namespace NRV\Event;


class Place{

    private int $id;
    private string $name;
    private string $adresse;
    private int $nbDebout;
    private int $nbAssis;
    private string $img;

    public function __construct(int $id, string $name, string $adresse, int $nbDebout, int $nbAssis, string $img){
        $this->id = $id;
        $this->name = $name;
        $this->adresse = $adresse;
        $this->nbDebout = $nbDebout;
        $this->nbAssis = $nbAssis;
        $this->img = $img;
    }

    
}
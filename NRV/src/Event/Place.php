<?php

namespace NRV\Event;


class Place{

    private int $id;
    private string $name;
    private string $address;
    private int $nbDebout;
    private int $nbAssis;
    private string $img;


    public function __construct(int $id, string $name, string $adresse, int $nbAssis, int $nbDebout, string $img){
        $this->id = $id;
        $this->name = $name;
        $this->adresse = $adresse;
        $this->nbAssis = $nbAssis;
        $this->nbDebout = $nbDebout;
        $this->img = $img;
    }


    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\Exception\InvalidPropertyNameException ("$at: invalid proprety");
    }

    public function __toString(): string {
        $res = sprintf(
            "Place:\nNom: %s\nAdresse: %s\nCapacité Standing place : %d\nSeat : %d\nImage: %s",
            $this->name,
            $this->adresse,
            $this->nbDebout,
            $this->nbAssis,
        );
        return $res .= <<<FIN
        <!DOCTYPE html>
        <html lang="fr">    
            <body>
                <img src="./NRV/Ressources/Images/$this->img" alt="">
            </body>
        </html>
    FIN;
    }
}
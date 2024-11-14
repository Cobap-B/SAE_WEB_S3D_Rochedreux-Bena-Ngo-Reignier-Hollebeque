<?php

namespace NRV\Event;


use NRV\Exception\InvalidPropertyNameException;

class Place{

    protected int $id;
    protected string $name;
    protected string $adresse;
    protected int $nbDebout;
    protected int $nbAssis;
    protected string $img;


    public function __construct(int $id, string $name, string $adresse, int $nbAssis, int $nbDebout, string $img){
        $this->id = $id;
        $this->name = $name;
        $this->adresse = $adresse;
        $this->nbAssis = $nbAssis;
        $this->nbDebout = $nbDebout;
        $this->img = $img;
    }


    /**
     * @throws InvalidPropertyNameException
     */
    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\Exception\InvalidPropertyNameException ("$at: invalid proprety");
    }

    public function __toString(): string {
        return <<<HTML
    <div class="place-container">
        <h2 class="place-title">{$this->name}</h2>
        <div class="place-details">
        <center>
            <img class="place-image" src="Ressources/Images/{$this->img}" alt="Image de {$this->name}">
        </center>
                <li><strong>Adresse:</strong> {$this->adresse}</li>
                <li><strong>Places debouts:</strong> {$this->nbDebout}</li>
                <li><strong>Places Assises:</strong> {$this->nbAssis}</li>
        </div>
    </div>
 

HTML;
    }

}
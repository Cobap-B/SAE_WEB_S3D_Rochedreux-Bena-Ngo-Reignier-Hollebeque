<?php
namespace NRV\Event;

class Party{

    private int $id;
    private string $date;
    private int $place;
    private array $shows;

    public function __construct(string $id, string $date, int $place, array $shows = []){
        $this->id = $id;    
        $this->date = $date;
        $this->place = $place;
        $this->shows = $shows;
    } 

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\exception\InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
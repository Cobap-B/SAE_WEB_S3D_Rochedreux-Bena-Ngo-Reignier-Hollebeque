<?php
namespace NRV\Event;

class FavoriteList{

    private int $id;
    private array $shows;

    public function __construct(string $id, array $shows = []){
        $this->id = $id;    
        $this->shows = $shows;
    } 

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\exception\InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
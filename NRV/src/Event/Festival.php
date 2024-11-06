<?php

namespace NRV\Event;

class Fastival{

    private int $id;
    private string $duration;
    private array $partys;

    public function __construct(string $id, string $duration, array $partys = []){
        $this->id = $id;    
        $this->duration = $duration;
        $this->partys = $partys;
    } 

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\exception\InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
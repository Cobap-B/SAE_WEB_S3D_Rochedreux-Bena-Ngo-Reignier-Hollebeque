<?php

namespace NRV\Event;

use NRV\exception\InvalidPropertyNameExcepetion;

class Festival{

    private int $id;
    private string $name;
    private string $duration;
    private array $partys;

    public function __construct(string $id, string $name, string $duration, array $partys = []){
        $this->id = $id;
        $this->name = $name;
        $this->duration = $duration;
        $this->partys = $partys;
    }

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }
}
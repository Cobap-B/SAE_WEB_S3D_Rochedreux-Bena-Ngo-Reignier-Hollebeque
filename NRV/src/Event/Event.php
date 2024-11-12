<?php
namespace NRV\Event;

abstract class Event{

    protected int $id;
    protected string $name;
    protected \DateTime $dateDebut;
    protected \DateTime $dateFin;

    public function __construct(int $id, string $name, string $dateDebut, string $dateFin){
        $this->id = $id;
        $this->name = $name;
        $this->dateDebut = \DateTime::createFromFormat('Y-m-d H:i:s',$dateDebut);
        $this->dateFin = \DateTime::createFromFormat('Y-m-d H:i:s',$dateFin);
    }

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\Exception\InvalidPropertyNameException ("$at: invalid proprety");
    }

    public function getDuration(){
        $duration = $this->dateDebut->diff($this->dateFin);
        return $duration;
    }
}
<?php
namespace NRV\Event;

class Show{

    private int $id;
    private string $category;
    private string $title;
    private string $artist;
    private int $duration;
    private string $description;
    private string $fileName;

    public function __construct(string $id, string $category, string $title, string $artist, int $duration, string $description, string $fileName){
        $this->id = $id;    
        $this->category = $category;
        $this->title = $title;
        $this->artist = $artist;
        $this->duration = $duration;
        $this->description = $description;
        $this->fileName = $fileName;
    } 

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\exception\InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
<?php
namespace NRV\Event;

class Show extends Event{

    private string $category;
    private string $artist;
    private string $description;
    private string $fileName;

    public function __construct(string $id, string $category, string $name, string $dateDebut, string $dateFin, string $artist, string $description, string $fileName){
        parent::__construct($id, $tname, $dateDebut, $dateFin); 

        $this->category = $category;
        $this->artist = $artist;
        $this->description = $description;
        $this->fileName = $fileName;
    } 

    public function __toString(): string {
        return sprintf(
            "Title: %s\nArtist: %s\nCategory: %d hours\nDescription: %s\n",
            $this->category,
            $this->title,
            $this->artist,
            $this->description,
        );
    }

}
<?php
namespace NRV\Event;

class Show extends Event{

    private string $category;
    private string $artist;
    private string $description;
    private string $audioPath;
    private string $imgPath;

    public function __construct(string $id, string $category, string $name, string $dateDebut, string $dateFin, string $artist, string $description, string $fileName, string $imgName){
        parent::__construct($id, $tname, $dateDebut, $dateFin); 

        $this->category = $category;
        $this->artist = $artist;
        $this->description = $description;
        $this->audioPath = $fileName;
        $this->imgPath = $imgName;
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
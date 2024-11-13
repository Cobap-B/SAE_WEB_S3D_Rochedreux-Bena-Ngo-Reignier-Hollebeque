<?php
namespace NRV\Event;

class Show extends Event{

    protected string $category;
    protected string $artist;
    protected string $description;
    protected string $audioPath;
    protected string $imgPath;

    public function __construct(string $id, string $category, string $name, string $dateDebut, string $dateFin, string $artist, string $description, string $fileName, string $imgName){
        parent::__construct($id, $name, $dateDebut, $dateFin);

        $this->category = $category;
        $this->artist = $artist;
        $this->description = $description;
        $this->audioPath = $fileName;
        $this->imgPath = $imgName;
    } 

    public function __toString(): string {
        $res = sprintf(
            "<ol>
                <li>Title : %s</li>\n
                <li>Artist : %s</li>\n
                <li>Category : %s</li>\n
                <li>Description : %s</li>\n
                <li>Start : %s</li>\n
                <li>Duration : %s</li>\n
            <ol>",
            $this->name,
            $this->artist,
            $this->category,
            $this->description,
            $this->dateDebut->format('Y-m-d H\h:i\m\i\n'),
            $this->getDuration()->format('%Hh:%imin')
        );
        $res .= <<<FIN
            <br>
            <img src="Ressources/Images/$this->imgPath" alt="">
            <br>
            <audio controls src="Ressources/Audios/$this->audioPath"></audio>
        FIN;

        return $res;
    }

}
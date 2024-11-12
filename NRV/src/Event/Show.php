<?php
namespace NRV\Event;

class Show extends Event{

    private string $category;
    protected string $artist;
    private string $description;
    private string $audioPath;
    private string $imgPath;

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
            "Title : %s\nArtist : %s\nCategory : %d\nDescription : %s\nStart : %s\nEnd : %s\n Duration : %s",
            $this->name,
            $this->artist,
            $this->category,
            $this->description,
            $this->dateDebut->format('Y-m-d H:i'),
            $this->dateFin->format('Y-m-d H:i'),
            $this->getDuration()->format('%H:%i')
        );
        $res .= <<<FIN
        <!DOCTYPE html>
        <html lang="fr">    
            <body>
                <img src="Ressources/Images/$this->imgPath" alt="">
                
                <figure>
                    <figcaption>Listen an extract</figcaption>
                    <audio controls src="Ressources/Audios/$this->audioPath"></audio>
                </figure>
                
            </body>
        </html>
        FIN;

        return $res;
    }

}
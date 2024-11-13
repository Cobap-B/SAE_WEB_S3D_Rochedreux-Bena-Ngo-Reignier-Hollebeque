<?php
namespace NRV\Event;

class Party extends Event{



    protected Place $place;
    protected array $shows;
    protected int $tarif;

    protected string $video_link;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, Place $place, int $tarif, array $shows = [], string $link){
        parent::__construct($id, $name, $dateDebut, $dateFin);
        $this->place = $place;
        $this->shows = $shows;
        $this->tarif = $tarif;
        $this->video_link = $link;
    }


    public function __toString(): string {
        $details = "";
        foreach ($this->shows as $show) {
            $details .= $show ."\n";
        }

        $res = <<<FIN
            <br>
            <video src="$this->video_link"></video>
            <br>
        FIN;

        $res .= sprintf(
            "Party (ID: %s)\nNom: %s\nDate début: %s\nDate fin: %s\nLieu: %s\nTarif: %d€\nSpectacles: %s",
            $this->id,
            $this->name,
            $this->dateDebut->format('Y-m-d H:i'),
            $this->dateFin->format('Y-m-d H:i'),
            $this->place,
            $this->tarif,
            $details
        );


        return $res;
    }
   

}
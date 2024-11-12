<?php
namespace NRV\Event;

class Party extends Event{



    private Place $place;
    private array $shows;
    private int $tarif;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, Place $place, int $tarif, array $shows = []){
        parent::__construct($id, $name, $dateDebut, $dateFin);
        $this->place = $place;
        $this->shows = $shows;
        $this->tarif = $tarif;
    }


    public function __toString(): string {
        $details = "";
        foreach ($this->party->shows as $show) {
            $details .= $show ."\n";
        }
        return sprintf(
            "Party (ID: %s)\nNom: %s\nDate début: %s\nDate fin: %s\nLieu: %s\nTarif: %d€\nSpectacles: %s",
            $this->id,
            $this->name,
            $this->dateDebut,
            $this->dateFin,
            $this->place,
            $this->tarif,
            $details
        );
    }
   

}
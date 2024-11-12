<?php

namespace NRV\Event;


class Festival extends Event{

    private array $partys;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, array $partys = []){
        parent::__construct($id, $name, $dateDebut, $dateFin);
        $this->partys = $partys;
    }


    public function __toString(): string {
        // Crée une chaîne de caractères pour chaque Party dans le tableau
        $partysList = array_map(fn($party) => (string)$party, $this->partys);
        $partysString = implode("\n\n", $partysList);

        return sprintf(
            "Festival :\nNom: %s\nDate début: %s\nDate fin: %s\nPartys:\n%s",
            $this->name,
            $this->dateDebut,
            $this->dateFin,
            $partysString
        );
    }
    
}
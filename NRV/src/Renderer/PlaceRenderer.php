<?php
declare(strict_types=1);

namespace NRV\Renderer;

use NRV\Event\Place;

class PlaceRenderer implements Render {

    private Place $place;


    public function __construct(Place $place) {
        $this->place = $place;
    }

    public function render(int $selector = self::COMPACT): string
    {
        switch ($selector) {
            case self::COMPACT:
                return $this->renderCompact();
            case self::LONG:
                return $this->renderLong();
            default:
                throw new \InvalidArgumentException("Invalid selector, please choose COMPACT or LONG.");
        }
    }

    public function renderCompact(): string {
        // Affiche uniquement le nom et l'adresse
        return sprintf(
            "Place: %s, Adresse: %s",
            $this->place->name,
            $this->place->adresse
        );
    }

    public function renderLong(): string {
        return (string)$this->place;
    }
}


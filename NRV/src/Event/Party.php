<?php
namespace NRV\Event;

use NRV\Renderer\ShowRenderer;

class Party extends Event{



    protected Place $place;
    protected array $shows;
    protected int $tarif;

    protected string $video_link;

    public function __construct(string $id, string $name, string $dateDebut, string $dateFin, Place $place, int $tarif, string $link, array $shows = []){
        parent::__construct($id, $name, $dateDebut, $dateFin);
        $this->place = $place;
        $this->shows = $shows;
        $this->tarif = $tarif;
        $this->video_link = str_replace("watch?v=","embed/",$link);
    }


    public function __toString(): string {
        $details = "";
        foreach ($this->shows as $show) {

            $render = new ShowRenderer($show);
            $details = $render->renderCompact();
        }

        return <<<HTML
            <center>
            <div class="party-container">
                <h2 class="party-title">{$this->name} </h2>
                <div class="party-details">
                    <div class="party-video">
                        <iframe width="420" height="315" src="{$this->video_link}" allowfullscreen></iframe>
                    </div>
                    <ul class="party-info">
                        <li><strong>Date début:</strong> {$this->dateDebut->format('Y-m-d H:i')}</li>
                        <li><strong>Date fin:</strong> {$this->dateFin->format('Y-m-d H:i')}</li>
                        <li><strong>Tarif:</strong> {$this->tarif} €</li>

                        {$this->place}
                    </ul>
                </div>
                <div class="party-shows">
                    <h3>Liste des spectacles</h3>
                    <ul>
                        {$details}
                    </ul>
                </div>
            </div>
        </center>
        HTML;
    }
}
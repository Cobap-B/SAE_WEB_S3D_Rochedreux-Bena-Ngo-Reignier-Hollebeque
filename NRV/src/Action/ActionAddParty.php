<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository;
use NRV\Event\Party;

class ActionAddParty extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){
            $html = <<<FIN
                <div>Enregistrer une nouvelle Party</div>
                <form method='POST' '?action=add-show' enctype="multipart/form-data">

                    <label for="partyName">Nom de la Party :</label>
                    <input type='text' name='partyName' required><br>

                    <label for="dateStart">La date du début de la Party :</label>
                    <input type='date' name='dateStart' required><br>
                    <label for="hourStart"> L'heure de début de la Party : </label>
                    <input type='time' name='hourStart' required><br>

                    <label for="dateEnd">La date de fin de la Party:</label>
                    <input type='date' name='dateEnd' required><br>
                    <label for="hourEnd"> L'heure de fin de la Party</label>
                    <input type='time' name='hourEnd' required><br>

                    <label for="price">Le prix de la party:</label>
                    <input type='number' name='price' required><br>
                    
                    <label for="video">video de la soirée (youtube.com) :</label>
                    <input type='text' name='video' required><br>

                    
            FIN;

            $bd = FestivalRepository::makeConnection();
            $pl = $bd->getAllLocation();
            $html .= 'Lieu : <select name="Location" size="1">';

            foreach($pl as $p){
                $id = $p->__get("id");
                $name = $p->__get("name");
                $html .= "<option value='$id'> $name </option>";
            }

            $html .= '</select><br><br>';
            $html .= <<<FIN
            <input type='submit' value='Enregistrer la Soirée'>
            </form>
            FIN;

        }else{

            $partyName = filter_var($_POST['partyName'] , FILTER_SANITIZE_SPECIAL_CHARS);
            $dateStart = $_POST['dateStart'];
            $dateEnd = $_POST['dateEnd'];
            $hourStart = $_POST['hourStart'];
            $hourEnd = $_POST['hourEnd'];
            $price = $_POST['price'];
            $idLoc = $_POST["Location"];
            $video = $_POST["video"];


            $r = FestivalRepository::makeConnection();
            $party = $r->saveParty($partyName,$dateStart,$dateEnd,$hourStart,$hourEnd,$idLoc,$price, $video);
            $html = "<div>Party ajoutée avec succès</div>";
        }
        return $html;
    }
}
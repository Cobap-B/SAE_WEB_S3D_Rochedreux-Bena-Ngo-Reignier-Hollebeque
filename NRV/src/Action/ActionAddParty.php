<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository;
use NRV\Event\Party;
use NRV\Exception\InvalidLinkException;

class ActionAddParty extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){
            $html = <<<FIN
                <div class="login-wrapper">
            <div class="login-container">
                <h2>Enregistrer une nouvelle Party</h2>
                <form method="POST" action="?action=add-party" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="partyName">Nom de la Party :</label>
                        <input type="text" id="partyName" name="partyName" required>
                    </div>

                    <div class="form-group">
                        <label for="dateStart">Date de début de la Party :</label>
                        <input type="date" id="dateStart" name="dateStart" required>
                    </div>
                    <div class="form-group">
                        <label for="hourStart">Heure de début de la Party :</label>
                        <input type="time" id="hourStart" name="hourStart" required>
                    </div>

                    <div class="form-group">
                        <label for="dateEnd">Date de fin de la Party :</label>
                        <input type="date" id="dateEnd" name="dateEnd" required>
                    </div>
                    <div class="form-group">
                        <label for="hourEnd">Heure de fin de la Party :</label>
                        <input type="time" id="hourEnd" name="hourEnd" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Prix de la Party :</label>
                        <input type="number" id="price" name="price" required>
                    </div>

                    <div class="form-group">
                        <label for="video">Vidéo de la soirée (YouTube) :</label>
                        <input type="text" id="video" name="video" required>
                    </div>
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
            <input type='submit' value='Enregistrer la Party'>
            </form>
            FIN;

        }else{
            try{
                $partyName = filter_var($_POST['partyName'] , FILTER_SANITIZE_SPECIAL_CHARS);
                $dateStart = $_POST['dateStart'];
                $dateEnd = $_POST['dateEnd'];
                $hourStart = $_POST['hourStart'];
                $hourEnd = $_POST['hourEnd'];
                $price = $_POST['price'];
                $idLoc = $_POST["Location"];

                //Preparation des variables pour voir si le lien Youtube est correct
                $verifLink = substr($_POST["video"], 0, 32);
                $verif = "https://www.youtube.com/watch?v=";

                //Si le lien est correct, il effectue la sauvegarde dans la BD
                if ($verifLink === $verif){
                    $video = $_POST["video"];
                    $r = FestivalRepository::makeConnection();
                    $party = $r->saveParty($partyName,$dateStart,$dateEnd,$hourStart,$hourEnd,$idLoc,$price, $video);
                    $html = "<div>Party ajoutée avec succès</div>";
                }
                //Si il n'est pas valide, renvoie une erreur
                else{
                    throw new InvalidLinkException('Lien Youtube invalide');
                }
            }catch(InvalidLinkException $e1){
                $html = $e1->getMessage();
            }
        }
        return $html;
    }
}
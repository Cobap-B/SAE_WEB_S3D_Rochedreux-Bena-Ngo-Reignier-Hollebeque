<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository;
use NRV\Event\Party;
use NRV\Exception\InvalidLinkException;

class ActionAddParty extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){
            $html = <<<FIN
            <br>
            <br>
            <br>
            <br>

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
                        <label for="price">Prix de la party :</label>
                        <input type="number" id="price" name="price" required>
                    </div>

                    <div class="form-group">
                        <label for="video">Vidéo de la party (YouTube) :</label>
                        <input type="text" id="video" name="video" required>
                    </div>

            FIN;

            $bd = FestivalRepository::makeConnection();
            $pl = $bd->getAllLocation();
            $html .= 'Lieu : <select id="locationSelect" name="Location" size="1" onchange="toggleTextBoxes()">';

            foreach($pl as $p){
                $id = $p->__get("id");
                $name = $p->__get("name");
                $html .= "<option value='$id'> $name </option>";
            }
            $html .= <<<FIN
            <option value="optionAutre">Autre (ajouter un nouveau lieu)</option>
            </select><br><br>
            FIN;

            //Bloc pour l'ajout d'un lieu quand l'option "Autres" est choisi
            $html .= <<<FIN
            <div id="BlocLoc" style="display: none; margin-top: 15px;">
                <div class="form-group">
                    <label for="locName">Nom du lieu :</label>
                    <input type="text" id="locName" name="locName" required><br>
                </div>
                
                <div class="form-group">
                    <label for="address">Adresse du lieu :</label>
                    <input type="text" id="address" name="address" required><br>
                </div>
                
                <div class="form-group">                
                    <label for="nbPlAs">Nombre places assis :</label>
                    <input type="number" id="nbPlAs" name="nbPlAs" required><br>
                </div>
            
                <div class="form-group">               
                    <label for="nbPlDe">Nombre de places debouts :</label>
                    <input type="number" id="nbPlDe" name="nbPlDe" required><br>
                </div>    
                    
            <div class="form-group">
                <label for="imgLoc">Image du lieu :</label>
                <input type='file' id="imgLoc" name='imgLoc' accept="image/png, image/jpeg, image/jpg" required>
            </div>
            </div>
            <input type='submit' value='Enregistrer la Party'>
            </form>
            <br>
            <br>
            <script>
            function toggleTextBoxes() {
                var elementLocationSelect = document.getElementById("locationSelect");
                var BlocLocDiv = document.getElementById("BlocLoc");
                var selectInput = BlocLocDiv.querySelectorAll('input');
                
                if (elementLocationSelect.value === "optionAutre") {
                    BlocLocDiv.style.display = "block";
                    selectInput.forEach(function (input) {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    BlocLocDiv.style.display = "none";
                    selectInput.forEach(function (input) {
                        input.removeAttribute('required');
                    });
                }
            }
            </script>   
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
                    if ($idLoc == 'optionAutre') {
                        $locName = filter_var($_POST['locName'], FILTER_SANITIZE_SPECIAL_CHARS);
                        $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS);
                        $nbPlAs = $_POST['nbPlAs'];
                        $nbPlDe = $_POST['nbPlDe'];

                        if($_FILES['imgLoc']['type'] === 'png' or $_FILES['imgLoc']['type'] === 'jpeg' or $_FILES['imgLoc']['type'] === 'jpg'){
                            $upload_dir = 'Ressources/Images/';
                            $tmp = $_FILES['imgLoc']['tmp_names'];
            
                            if ($_FILES['imgLoc']['error'] === UPLOAD_ERR_OK){
                                $dest = $upload_dir . $_FILES['imgLoc']['name'];
                                move_uploaded_file($tmp, $dest);
                            }
                        }
                        $imgLoc = $_FILES['imgLoc']['name'];
                        $video = $_POST["video"];
                        $r = FestivalRepository::makeConnection();
                        $party = $r->savePartyWithNewLoc($partyName,$dateStart,$dateEnd,$hourStart,$hourEnd,$price,$video, $locName,$address,$nbPlAs,$nbPlDe,$imgLoc);
                    }else{
                        $video = $_POST["video"];
                        $r = FestivalRepository::makeConnection();
                        $party = $r->saveParty($partyName,$dateStart,$dateEnd,$hourStart,$hourEnd,$idLoc,$price,$video);
                    }
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
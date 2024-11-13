<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository;
use NRV\Event\Show;

class ActionAddShow extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){
            $html = <<<FIN
                <div>Enregistrer un nouveau spectacle</div>
                <form method='POST' '?action=add-show' enctype="multipart/form-data">

                    <label for="categorie">Categorie du spectacle :</label>
                    <input type='text' name='categorie' required><br>

                    <label for="title">Nom du spectacle :</label>
                    <input type='text' name='title' required><br>

                    <label for="artist">L'artiste du spectacle :</label>
                    <input type='text' name='artist'><br>

                    <label for="dateStart">L'heure et la date du début du spectacle :</label>
                    <input type='date' name='dateStart' required><br>
                    <label for="hourStart"> L'heure de début dushow : </label>
                    <input type='time' name='hourStart' required><br>

                    <label for="dateEnd">L'heure et la date de la fin du spectacle:</label>
                    <input type='date' name='dateEnd' required><br>
                    <label for="hourEnd"> L'heure de fin du show : </label>
                    <input type='time' name='hourEnd' required><br>

                    <label for="desc">La description du spectacle :</label>
                    <input type='text' name='desc' required><br>

                    <label for='img'>L'image du spectacle :</label>
                    <input type='file' name='img' accept="png/jpeg/jpg" required><br>

                    <label for='audio'>L'audio du spectacle :</label>
                    <input type='file' name='audio' accept="mp3/mpeg" required><br>


                    <input type='submit' value='Enregistrer le Show'>
                </form> 
            FIN;
        }else{
            $categorie = filter_var($_POST['categorie'] , FILTER_SANITIZE_SPECIAL_CHARS);
            $title = filter_var($_POST['title'] , FILTER_SANITIZE_SPECIAL_CHARS);
            $artist = filter_var($_POST['artist'] , FILTER_SANITIZE_SPECIAL_CHARS);

            $dateStart = $_POST['dateStart'];
            $dateEnd = $_POST['dateEnd'];
            $hourStart = $_POST['hourStart'];
            $hourEnd = $_POST['hourEnd'];
            var_dump($dateStart);
            var_dump($hourStart);
            $desc = filter_var($_POST['desc'] , FILTER_SANITIZE_SPECIAL_CHARS);

            
            if($_FILES['img']['type'] === 'png' or $_FILES['img']['type'] === 'jpeg'){
                $upload_dir = 'Ressources/Images/';
                $tmp = $_FILES['img']['tmp_names'];

                if ($_FILES['img']['error'] === UPLOAD_ERR_OK){
                    $dest = $upload_dir . $_FILES['img']['name'];
                    move_uploaded_file($tmp, $dest);
                }
            }
            $image = $_FILES['img']['name'];


            if($_FILES['audio']['type'] === 'mpeg'){
                $upload_dir = 'Ressources/Audios/';
                $tmp = $_FILES['audio']['tmp_names'];

                if ($_FILES['audio']['error'] === UPLOAD_ERR_OK){
                    $dest = $upload_dir . $_FILES['audio']['name'];
                    move_uploaded_file($tmp, $dest);
                }
            }
            $audio = $_FILES['audio']['name'];
            
            $r = FestivalRepository::makeConnection();
            $show = $r->saveShow($categorie,$title,$artist,$dateStart,$dateEnd,$hourStart,$hourEnd,$desc,$audio,$image);
            $html = "<div>Show ajoutée</div>";

        }

        return $html;
    }
}

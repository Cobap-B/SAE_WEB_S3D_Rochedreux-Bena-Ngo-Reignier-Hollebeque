<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository;
use NRV\Event\Show;

class ActionAddShow extends Action{

    public function execute(): string{
        if (! isset($_SESSION['user']) || isset($_SESSION['user']['id']) == 0){
            return "<div>Il faut être admin</div>";
        }
        if ($this->http_method === 'GET'){
            $html = <<<FIN
            <br>
            <br>
            <br>
            <br>
                <body>
                <div class="login-wrapper">
                    <div class="login-container">
                        <h2>Enregistrer un nouveau spectacle</h2>
                        <form method='POST' action='?action=add-show' enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="categorie">Catégorie du spectacle :</label>
                                <input type='text' id="categorie" name='categorie' required>
                            </div>

                            <div class="form-group">
                                <label for="title">Nom du spectacle :</label>
                                <input type='text' id="title" name='title' required>
                            </div>

                            <div class="form-group">
                                <label for="artist">L'artiste du spectacle :</label>
                                <input type='text' id="artist" name='artist'>
                            </div>

                            <div class="form-group">
                                <label for="dateStart">Date de début du spectacle :</label>
                                <input type='date' id="dateStart" name='dateStart' required>
                            </div>
                            <div class="form-group">
                                <label for="hourStart">Heure de début du spectacle :</label>
                                <input type='time' id="hourStart" name='hourStart' required>
                            </div>

                            <div class="form-group">
                                <label for="dateEnd">Date de fin du spectacle :</label>
                                <input type='date' id="dateEnd" name='dateEnd' required>
                            </div>
                            <div class="form-group">
                                <label for="hourEnd">Heure de fin du spectacle :</label>
                                <input type='time' id="hourEnd" name='hourEnd' required>
                            </div>

                            <div class="form-group">
                                <label for="desc">Description du spectacle :</label>
                                <input type='text' id="desc" name='desc' required>
                            </div>

                            <div class="form-group">
                                <label for='img'>Image du spectacle :</label>
                                <input type='file' id="img" name='img' accept="image/png, image/jpeg" required>
                            </div>

                            <div class="form-group">
                                <label for='audio'>Audio du spectacle :</label>
                                <input type='file' id="audio" name='audio' accept="image/mp3, image/mpeg" required>
                            </div>

                            <button type='submit'>Enregistrer le spectacle</button>
                        </form>
                    </div>
                    </div>
                    <br>
                    <br>
                        
                </body>
                </html>
            FIN;
        }else{
            $categorie = filter_var($_POST['categorie'] , FILTER_SANITIZE_SPECIAL_CHARS);
            $title = filter_var($_POST['title'] , FILTER_SANITIZE_SPECIAL_CHARS);
            $artist = filter_var($_POST['artist'] , FILTER_SANITIZE_SPECIAL_CHARS);

            $dateStart = $_POST['dateStart'];
            $dateEnd = $_POST['dateEnd'];
            $hourStart = $_POST['hourStart'];
            $hourEnd = $_POST['hourEnd'];
            $desc = filter_var($_POST['desc'] , FILTER_SANITIZE_SPECIAL_CHARS);
        
            if($_FILES['img']['type'] == 'image/png' or $_FILES['img']['type'] == 'image/jpeg' or $_FILES['img']['type'] == 'image/jpg'){
                $upload_dir = 'Ressources/Images/';
                $tmp = $_FILES['img']['tmp_name'];

                if ($_FILES['img']['error'] === UPLOAD_ERR_OK){
                    
                    $dest = $upload_dir.$_FILES['img']['name'];
                    move_uploaded_file($tmp, $dest);
                }
            }
            $image = $_FILES['img']['name'];


            if($_FILES['audio']['type'] == 'audio/mpeg' or $_FILES['audio']['type'] == 'audio/mp3'){
                $upload_dir = 'Ressources/Audios/';
                $tmp = $_FILES['audio']['tmp_name'];

                if ($_FILES['audio']['error'] == UPLOAD_ERR_OK){
                    $dest = $upload_dir . $_FILES['audio']['name'];
                    move_uploaded_file($tmp, $dest);
                }
            }
            $audio = $_FILES['audio']['name'];
            
            $r = FestivalRepository::makeConnection();
            $show = $r->saveShow($categorie,$title,$artist,$dateStart,$dateEnd,$hourStart,$hourEnd,$desc,$audio,$image);
            $html = "<div>Spectacle ajoutée</div>";

        }

        return $html;
    }
}

<?php

namespace NRV\action;

use NRV\Auth\AuthnProvider;
use NRV\Repository\FestivalRepository;

class ActionDisplayFavorite extends Action{

    public function execute(): string{
        $html = "";
        $r = FestivalRepository::makeConnection();
        //si l'utilisateur est connecté
        if (isset($_SESSION['userId'])) {
            if ($this->http_method === 'GET') {
                $h = $r->displayFavorite("");
                $html .= $h;
                /*return <<<END
                <form method="POST" action="?action=display-favorite">
                <label for="id">ID de la liste de préférence :</label>
                <input type="number" name="id">
                <button type="submit"> Afficher préférence </button>
                </form>
            END;*/
            } else {
                $html = "<div>Affichage des préférences</div>";
                $id = filter_var($_POST['id']);
                if (AuthnProvider::authenticate($_SESSION['userId'], $id)) {
                    $h = $r->displayFavorite($id);
                    $html .= $h;
                    return $html;
                } else {
                    return "<div>Pas de Favori</div>";
                }
            }
        } /*else {
            //afficher la liste sans être connecter
            if ($this->http_method === 'GET') {
                return <<<END
                <form method="POST" action="?action=display-favorite">
                <button type="submit"> Afficher préference </button>
                </form>
            END;
            } else {
                $html = "<div>Affichage des préférences</div>";
                $id = filter_var($_POST['id']);
                $html = $r->displayFavorite($id);
                return $html;
            }
        }*/
        return $html
    }
}
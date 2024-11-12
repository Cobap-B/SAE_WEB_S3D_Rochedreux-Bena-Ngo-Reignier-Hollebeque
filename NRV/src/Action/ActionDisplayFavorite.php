<?php

namespace NRV\action;

use NRV\Auth\AuthnProvider;
use NRV\Repository\FestivalRepository;

class ActionDisplayFavorite extends Action{

    public function execute(): string
    {
        //si l'utilisateur est connecté
        if (isset($_SESSION['userId'])) {
            if ($this->http_method === 'GET') {
                return <<<END
                <form method="POST" action="?action=display-favorite">
                <label for="id">ID de la liste de préférence :</label>
                <input type="number" name="id">
                <button type="submit"> Afficher préference </button>
                </form>
            END;
            } else {
                $html = "<div>Affichage des préférences</div>";
                $id = filter_var($_POST['id']);
                if (AuthnProvider::authenticate($_SESSION['userId'], $id)) {
                    $r = FestivalRepository::getInstance();

                    return $html;
                } else {
                    return "<div>Pas de Favori</div>";
                }
            }
            return $html;
        } else {
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
                return $html;
            }
        }
    }
}
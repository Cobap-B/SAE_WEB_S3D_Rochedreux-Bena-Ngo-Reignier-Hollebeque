<?php

namespace NRV\Action;

use NRV\Repository\FestivalRepository as FestivalRepository;

class ActionModifyParty extends Action
{

    public function execute(): string
    {
        $html = "";
        if ($this->http_method === 'GET' || $this->http_method === 'POST') {
            $bd = FestivalRepository::makeConnection();
            $shows = $bd->displayAllShow();
            $parties = $bd->displayParty();
            $successMessage = "";

            // Vérifier si la requête est POST et que l'ajout est effectué
            if ($this->http_method === 'POST') {
                $txt = $bd->insertPartyToShow($_POST['parties'], $_POST['shows']);
                $successMessage = "<p class='success'>Spectacle ajouté avec succès à la soirée !</p>";
            }

            // Générer le HTML
            $html = <<<HTML
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier une Soirée</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="login-wrapper">
            <div class="login-container">
                <h2>Modifier une Soirée</h2>
                <div class = sucess>
                $successMessage
                </div>

                <form method="POST" action="?action=modif-party" id="form">
                    <div class="form-group">
                        <label for="shows">Choisissez un spectacle :</label>
                        <select name="shows" id="shows">
HTML;
            foreach ($shows as $show) {
                $n = htmlspecialchars($show->name);
                $id = $show->id;
                $html .= "<option value=\"$id\">$n</option>";
            }

            $html .= <<<HTML
                        </select>
                    </div>

                    <!-- Formulaire de sélection des soirées -->
                    <div class="form-group">
                        <label for="parties">Choisissez une soirée :</label>
                        <select name="parties" id="party">
HTML;
            foreach ($parties as $party) {
                $n = htmlspecialchars($party->name);
                $id = $party->id;
                $html .= "<option value=\"$id\">$n</option>";
            }

            $html .= <<<HTML
                        </select>
                    </div>

                    <button type="submit" name="valider">Ajouter le spectacle à la soirée</button>
                </form>
            </div>
        </div>
    </body>
    </html>
    HTML;
        }

     else {
            $bd = FestivalRepository::makeConnection();
            $txt = $bd->insertPartyToShow($_POST['parties'], $_POST['shows']);
            $html = $txt;
        }
        return $html;
    }


}
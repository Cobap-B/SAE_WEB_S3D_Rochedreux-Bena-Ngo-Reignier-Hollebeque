<?php

namespace NRV\action;

use NRV\Auth\AuthnProvider;

class ActionAuthentication extends Action{

    private String $error;

    public function execute(): string{
        if ($this->http_method === 'GET'){
            $html = <<<FIN
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Se connecter</title>
        </head>

        <div class="login-container">
            <h2>Se connecter</h2>
            <form method="POST" action="?action=authentication">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="mdp" name="mdp" required>
                </div>
                <button type="submit">Se connecter</button>
                <footer> 
                    <br>  
                    <a class="dropdown-item" href="?action=register">S'enregistrer</a>
                </footer>
            </form>
        </div>
        </html>  
    FIN;
        }
        else{
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
            $mdp = $_POST['mdp'];

            AuthnProvider::authenticate($email,$mdp);

            $html = "<div>Vous êtes connecté</div>";
        }
        return $html;
    }
}
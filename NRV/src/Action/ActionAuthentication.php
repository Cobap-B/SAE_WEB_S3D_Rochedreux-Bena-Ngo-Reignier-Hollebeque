<?php

namespace NRV\action;

use NRV\Auth\AuthnProvider;

class ActionAuthentication extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){

            $html = <<<FIN
                <div>Se connecter</div>
                <form method='POST' '?action=authentication'>
                <input type='email' name='email' required>
                <input type='password' name='mdp' required>
                <input type='submit' value='se connecter'>
                </form> 
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
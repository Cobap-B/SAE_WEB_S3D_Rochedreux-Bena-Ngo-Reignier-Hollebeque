<?php
namespace NRV\Action;

use PDO;
use NRV\Repository\FestivalRepository;

class ActionRegister extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute(): string {
        $errorMessage = "";
        $emailValue = "";

        if ($this->http_method === "POST") {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password1 = $_POST['mdp'];
            $password2 = $_POST['mdp2'];
            // on remplit direct l'adresse mail si y a une erreur, ça évite de la retaper
            $emailValue = htmlspecialchars($email);

            if ($password1 === $password2) {
                // Inscription réussie
                $errorMessage = "<p>" . \NRV\Auth\AuthnProvider::register($email, $password1, 1) . "</p>";
            } else {
                $errorMessage = "<p class='error'>Les mots de passe ne correspondent pas.</p>";
            }
        }

        // Génération du formulaire HTML
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Créer un compte</title>
            
           <style> 
           .error{
           color: red;
           
           }
           </style>
            
        </head>
        <body>
            <div class="login-wrapper">
            <div class="login-container">
                <h2>Créer un compte</h2>
                <div class=" error">
                <i>$errorMessage</i>
                </div>
                <form method="POST" action="?action=register">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="$emailValue" required>
                    </div>
                    <div class="form-group">
                        <label for="mdp">Mot de passe</label>
                        <input type="password" id="mdp" name="mdp" required>
                    </div>
                    <div class="form-group">
                        <label for="mdp2">Confirmer votre mot de passe</label>
                        <input type="password" id="mdp2" name="mdp2" required>
                    </div>
                    <button type="submit">S'inscrire</button>
                    <div class="register"> 

                        <br>  
                        <a class="dropdown-item" href="?action=authentication">Se connecter</a>
                    </div>    
                </form>
            </div>
            </div>
        </body>
        </html>
        HTML;
    }
}

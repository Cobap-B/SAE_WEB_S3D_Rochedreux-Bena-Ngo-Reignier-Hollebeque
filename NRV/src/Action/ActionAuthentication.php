<?php

namespace NRV\Action;

use NRV\Auth\AuthnProvider;

class ActionAuthentication extends Action {

    private string $errorMessage = ""; // Variable d'erreur

    public function execute(): string {
        // Si la méthode HTTP est GET, afficher le formulaire d'authentification
        if ($this->http_method === 'GET') {
            return $this->getFormHTML();
        } else {
            // Si la méthode HTTP est POST, essayer de s'authentifier
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'];

            // Tentative d'authentification
            $succes = AuthnProvider::authenticate($email, $mdp);

            // Si l'authentification échoue
            if ($succes !== true) {
                // Mise à jour du message d'erreur
                if ($succes == "Vous êtes connecté"){
                    $this->errorMessage = "<p class='sucess'>$succes</p>";
                }else{
                    $this->errorMessage = "<p class='error'>$succes</p>";
                }
                
            } else {
                // Authentification réussie, rediriger ou autre action
                header("Location: /default"); // Exemple de redirection après connexion réussie
                exit;
            }

            // Retourne le HTML avec le message d'erreur mis à jour
            return $this->getFormHTML();
        }
    }

    // Méthode pour générer le HTML du formulaire avec les erreurs
    private function getFormHTML(): string {
        return <<<FIN
            <div class="login-wrapper">
            <div class="login-container">
                <h2>Se connecter</h2>
                <!-- Affichage des erreurs s'il y en a -->
                <div>
                    <i>{$this->errorMessage}</i>
                </div>
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
            </div>
    FIN;
    }
}

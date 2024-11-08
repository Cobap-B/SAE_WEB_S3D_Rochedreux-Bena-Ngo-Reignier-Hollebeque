<?php
namespace NRV\Action;
use PDO;
use NRV\Repository\FestivalRepository;

class ActionRegister extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res="";
        if($this->http_method == "GET"){
            $res = <<<FIN
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>Se connecter</title>
            </head>

            <div class="login-container">
                <h2>Créer un compte</h2>
                <form method="POST" action="?action=register">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" required>
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
                    <footer> 
                        <br>  
                        <a class="dropdown-item" href="?action=authentication">Se connecter</a>
                    </footer>
                </form>
            </div>
            </html>  
            FIN;
        }else{
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p1= $_POST['mdp'];
            $p2 = $_POST['mdp2'];
            if($p1 === $p2){
                $res = "<p>".\NRV\Auth\AuthnProvider::register($e, $p1)."</p>";
            }else{
                $res .= <<<FIN
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Se connecter</title>
                </head>

                <div class="login-container">
                    <h2>Créer un compte</h2>
                    <form method="POST" action="?action=register">
                        <header>
                        <p>Mot de passe 1 et 2 différents</p>
                        </header>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" required>
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
                        <footer> 
                            <br>  
                            <a class="dropdown-item" href="?action=authentication">Se connecter</a>
                        </footer>
                    </form>
                </div>
                </html>  
                FIN;
            }
        }
        return $res;
    }
}
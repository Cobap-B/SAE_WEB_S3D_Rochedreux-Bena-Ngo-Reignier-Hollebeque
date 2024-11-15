<?php
namespace NRV\Dispatcher;
use NRV\Action as act;

class Dispatcher {
    public string $action;
    public string $css_action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
        $this->css_action = "";
    }

    // Méthode pour rendre la page avec le contenu spécifique
    private function renderPage(string $html): void {
        // Définition de la structure de la navbar avec le lien pour se déconnecter ou s'authentifier
        $str = "";
        $bool = isset($_SESSION['user']);
        if (isset($_SESSION['user']['email'])) {
            $m = $_SESSION['user']['email'];
            $str = <<<FIN
             '<li class="nav-item dropdown">
                        <a class="nav-link">Connected</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?action=disconnect">Log Out</a></li>
                            </ul>
                      </li> ';
            FIN;

        }
        else{
            $str = '<li class="nav-item"><a class="nav-link" href="?action=authentication">Authentication</a></li>';
        }
        echo <<<FIN
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Festival NRV</title>
                <link rel="stylesheet" href="./CSS/rendupage.css">

                <link rel="stylesheet" href="./CSS/{$this->css_action}">
                <link rel="icon" href="Ressources/Images/pipotam_le_vrai.png" type="image/png">
            </head>
            <body>
            <div class="background-container"></div>
            <div class="overlay"></div>
                <div class="container"> 
                    <nav>
                        <ul class="nav">
                            <div class="nav-left">
                                <li class="nav-item"><a class="nav-link" href="?action=default"> ACCUEIL </a></li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link">DECOUVRIR</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?action=display-show"> Nos Spectacles </a></li>
                                        <li><a class="dropdown-item" href="?action=display-party"> Nos Soirées </a></li>
                                        <li><a class="dropdown-item" href="?action=display-favorite"> Vos Favoris </a></li>
                                    </ul>
                                </li>
        FIN;
                                if ($bool){
                                    if ($_SESSION['user']['role'] > 1){
                                        echo <<<FIN
                                        <li class="nav-item dropdown">
                                            <a class="nav-link">MODIFIER UN CONTENU </a>
                                            <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="?action=add-party">Organiser une soirée</a></li>
                                            <li><a class="dropdown-item" href="?action=modif-party">Modifier une soirée</a></li>
                                            <li><a class="dropdown-item" href="?action=add-show">Ajouter un spectacle</a></li>
                                            </ul>
                                        </li>
                                        FIN;
                                    }
                                    if ($_SESSION['user']['role'] > 2){
                                        echo <<<FIN
                                        <li class="nav-item"><a class="nav-link" href="?action=add-staff">CREER UN COMPTE STAFF</a></li>
                                        FIN;
                                    }
                                }
                            echo <<<FIN
                            </div>
                            <div class="nav-right">
                            FIN;
                            if (isset($_SESSION['user']['email'])) {
                                echo <<<FIN
                                <li class="nav-item"><a class="nav-link">$m</a></li>    
                                FIN;
                            }
                            ECHO <<<FIN
                            $str
                            </div>
                        </ul>
                    </nav>
                         
                    <div class="main-content">
                        $html
                    </div> 
                                                                                
                </div>         
                <footer>
                    <div class="footer-content">
                        &copy; 2024 Festival NRV. Tous droits réservés.
                    </div>
                </footer> 
            </body>  
        </html>
    FIN;
    }

    public function run() {
        $a = null;
        
        // Choix de l'action et de la feuille CSS
        switch ($this->action) {
            case 'favorite':
                $a = new act\ActionAddFavorite();
                $this->css_action = "formulaire.css";
                break;
            case 'add-show':    
                $a = new act\ActionAddShow();
                $this->css_action = "formulaire.css";
                break;
            case 'add-party':
                $a = new act\ActionAddParty();
                $this->css_action = "formulaire.css";
                break;
            case 'authentication':
                $a = new act\ActionAuthentication();
                $this->css_action = "formulaire.css";
                break;
            case 'display-show':
                $a = new act\ActionDisplayShow();
                $this->css_action = "display_show2.css";
                break;
            case 'display-party':
                $a = new act\ActionDisplayParty();
                $this->css_action = "display_party.css";
                break;
            case 'display-une-party':
                    $a = new act\ActionDisplayUneParty();
                $this->css_action = "formulaire.css";
                    break;
            case 'display-favorite':
                $a = new act\ActionDisplayFavorite();
                $this->css_action = "display_show2.css";
                break;
            case 'register':
                $a = new act\ActionRegister();
                $this->css_action = "formulaire.css";
                break;
            case 'modif-party':
                $a = new act\ActionModifyParty();
                $this->css_action = "formulaire.css";
                break;
            case 'disconnect':
                $a = new act\ActionDisconnect();
                $this->css_action = "formulaire.css";
                break;
            case 'add-staff':
                $a = new act\ActionAddStaff();
                $this->css_action = "formulaire.css";
                break;
            default:
                $a = new act\ActionDefault();
                $this->css_action = "default.css";
                break;
        }

        $this->renderPage($a->execute());
    }
}

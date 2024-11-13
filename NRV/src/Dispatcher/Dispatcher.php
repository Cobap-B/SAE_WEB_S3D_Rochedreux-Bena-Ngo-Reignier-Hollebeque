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
            $str =  '<li class="nav-item dropdown">
                        <a class="nav-link">Connected</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?action=disconnect">Log Out</a></li>
                            </ul>
                      </li> ';

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
                <div class="container"> 
                    <nav>
                        <ul class="nav">
                            <div class="nav-left">
                                <li class="nav-item"><a class="nav-link" href="?action=default">HOME</a></li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link">DISPLAY</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?action=display-show">Shows</a></li>
                                        <li><a class="dropdown-item" href="?action=display-party">Partys</a></li>

                                        <li><a class="dropdown-item" href="?action=display-favorite">Favorites</a></li>
                                        <li><a class="dropdown-item" href="?action=display-program">Program</a></li>
                                    </ul>
                                </li>
        FIN;
                                if ($bool){
                                    if ($_SESSION['user']['id'] > 1){
                                        echo <<<FIN
                                        <li class="nav-item dropdown">
                                            <a class="nav-link">MODIFY CONTENT</a>
                                            <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="?action=add-party">Create Party</a></li>
                                            <li><a class="dropdown-item" href="?action=modif-party">Edit Party</a></li>
                                            <li><a class="dropdown-item" href="?action=add-show">Create Show</a></li>
                                            <li><a class="dropdown-item" href="?action=modif-show">Edit Show</a></li>
                                            <li><a class="dropdown-item" href="?action=cancel-show">Cancel Show</a></li>
                                            </ul>
                                        </li>
                                        FIN;
                                    }
                                }
                            echo <<<FIN
                            </div>
                            <div class="nav-right">                    
                            $str
                            </div>
                        </ul>
                    </nav>
                         
                    <div class="main-content">
                        $html
                    </div>                   
                    
                </div>
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
                $this->css_action = "favorite.css";
                break;
            case 'add-show':    
                $a = new act\ActionAddShow();
                $this->css_action = "add_show.css";
                break;
            case 'add-party':
                $a = new act\ActionAddParty();
                break;
            case 'authentication':
                $a = new act\ActionAuthentication();
                $this->css_action = "page_connexion.css";
                break;
            case 'del-show':
                $a = new act\ActionDeleteShow();
                $this->css_action = "del_show.css";
                break;
            case 'display-show':
                $a = new act\ActionDisplayShow();
                $this->css_action = "display_show2.css";
                break;
            case 'display-party':
                $a = new act\ActionDisplayParty();
                $this->css_action = "display_party.css";
                break;
            case 'display-favorite':
                $a = new act\ActionDisplayFavorite();
                $this->css_action = "display_favorite.css";
                break;
            case 'display-program':
                $a = new act\ActionDisplayProgram();
                $this->css_action = "display_program.css";
                break;
            case 'register':
                $a = new act\ActionRegister();
                $this->css_action = "page_connexion.css";
                break;
            case 'modif-show':
                $a = new act\ActionModifyShow();
                $this->css_action = "modif_show.css";
                break;
            case 'modif-party':
                $a = new act\ActionModifyParty();
                break;
            case 'disconnect':
                $a = new act\ActionDisconnect();
                $this->css_action = "page_connexion.css";
                break;
            default:
                $a = new act\ActionDefault();
                $this->css_action = "default.css";
                break;
        }

        $this->renderPage($a->execute());
    }
}

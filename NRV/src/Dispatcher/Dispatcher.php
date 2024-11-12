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

    private function renderPage(string $html): void {
        $str = "";
        if (isset($_SESSION['user']['email'])) {
            $str =  '<li class="nav-item dropdown">
                        <a class="nav-link">Connected</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?action=disconnect">Disconnection</a></li>
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
                <title>Festival NRV</title>
                <link rel="stylesheet" href="./css/rendupage.css">
                <link rel="stylesheet" href="./css/{$this->css_action}">
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
                                        <li><a class="dropdown-item" href="?action=display-favorite">Favorites</a></li>
                                        <li><a class="dropdown-item" href="?action=display-program">Program</a></li>
                                    </ul>
                                </li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link">MODIFY CONTENT</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?action=modify-party">Party</a></li>
                                        <li><a class="dropdown-item" href="?action=modify-show">Show</a></li>
                                    </ul>
                                </li>
                                
                            </div>
                            <div class="nav-right">                    
                            $str
                            </div>
                        </ul>
                    </nav>
                    
                    
                    
                    <br> 
                    
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
                break;
            case 'add-show':
                $a = new act\ActionAddShow();
                break;
            case 'authentication':
                $a = new act\ActionAuthentication();
                break;
            case 'del-show':
                $a = new act\ActionDeleteShow();
                break;
            case 'display-show':
                $a = new act\ActionDisplayShow();
                break;
            case 'display-party':
                $a = new act\ActionDisplayParty();
                break;
            case 'display-favorite':
                $a = new act\ActionDisplayFavorite();
                break;
            case 'display-program':
                $a = new act\ActionDisplayProgram();
                break;
            case 'register':
                $a = new act\ActionRegister();
                break;
            case 'modif-show':
                $a = new act\ActionModifyShow();
                break;
            default:
                $a = new act\ActionDefault();
                break;
        }

        $this->renderPage($a->execute());
    }
}

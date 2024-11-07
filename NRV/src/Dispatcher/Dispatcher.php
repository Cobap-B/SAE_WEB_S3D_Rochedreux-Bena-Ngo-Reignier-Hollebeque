<?php
namespace NRV\dispatcher;
use \NRV\action as act;


class Dispatcher{

    public string $action;

    public function __construct(){
        if (isset($_GET['action'])){
            $this->action = $_GET['action'];
        }else
            $this->action = "default";
    }

    private function renderPage(string $html): void
    //<link rel="stylesheet" href="css/rendupage.css">
    {
        echo <<<FIN
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>DEFI</title>
                
            </head>
            <body>
                <div class="container"> 
                    <h1 class="text-primary">NRV</h1>
                    <nav>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link" href="?action=default">Accueil</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=favorite">Favorite</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=add-show">Add Show</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=authentication">Authentication</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=del-show">Del-show</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=display-show">Display-show</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=display-favorite">Display-favorite</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=display-program">Display-program</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=modif-show">Modif-show</a></li>
                        </ul>
                    </nav>
                    <br>
                    $html
                </div>
            </body>
        </html>
    FIN;
    }
    
    public function run(){
        $a = null;
        switch ($this->action ) {
            case 'favorite' :
                $a = new act\ActionAddFavorite();
                break ;
            case 'add-show' :
                $a = new act\ActionAddShow();
                break ;
            case 'authentication' :
                $a = new act\ActionAuthentication();
                break ;
            case 'del-show' :
                $a = new act\ActionDeleteShow();
                break ;
            case 'display-show' :
                $a = new act\ActionDisplayShow();
                break ;
            case 'display-favorite' :
                $a = new act\ActionDisplayFavorite();
                break ;
            case 'display-program' :
                $a = new act\ActionDisplayProgram();
                break ;
            case 'modif-show' :
                $a = new act\ActionModifyShow();
                break ;
            default :
                $a = new act\ActionDefault();
                break;
        }
        $this->renderPage($a->execute());
    }

}
<?php
namespace NRV\Dispatcher;
use NRV\Action as act;


class Dispatcher{

    public string $action;

    public function __construct(){
        if (isset($_GET['action'])){
            $this->action = $_GET['action'];
        }else
            $this->action = "default";
    }

    private function renderPage(string $html): void
    {
        echo <<<FIN
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>Festival NRV</title>
                    <link rel="stylesheet" href="./css/rendupage.css">

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
                                <li class="nav-item"><a class="nav-link" href="?action=authentication">Authentication</a></li>
                            </div>
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
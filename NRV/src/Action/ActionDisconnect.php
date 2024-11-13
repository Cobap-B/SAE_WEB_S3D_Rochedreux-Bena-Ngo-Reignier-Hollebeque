<?php
namespace NRV\action;

class ActionDisconnect extends Action{
    public function execute(): string{
        unset($_SESSION['user']);
        return <<<FIN
            <html lang="fr">
            <head>  
            
            </head>
            <body>
            <div class = login-wrapper>
                <div class = login-container>
                    Vous êtes déconnecté.
                </div>
            </div>
            </body>
            </html>
        FIN;
    }
}
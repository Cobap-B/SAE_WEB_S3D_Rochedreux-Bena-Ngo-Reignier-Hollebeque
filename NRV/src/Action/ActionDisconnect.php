<?php
namespace NRV\action;

class ActionDisconnect extends Action{
    public function execute(): string{
        unset($_SESSION['user']);
        return <<<FIN
            Déconnecté.
        FIN;
    }
}
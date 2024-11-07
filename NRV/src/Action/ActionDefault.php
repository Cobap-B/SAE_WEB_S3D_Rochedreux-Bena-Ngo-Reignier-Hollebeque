<?php
namespace NRV\action;

class ActionDefault extends Action {

    public function execute() {
        return <<<FIN
            <div>Bienvenue</div>
        FIN;
        
    }
}

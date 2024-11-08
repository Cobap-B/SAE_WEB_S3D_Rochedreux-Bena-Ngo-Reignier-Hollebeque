<?php
namespace NRV\action;

class ActionDisplayProgram extends Action {

    public function execute() {
        return <<<FIN
            <div>Display</div>           
        FIN;
        if ($this->http_method === 'GET'){
        }
    }
}

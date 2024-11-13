<?php
namespace NRV\Action;

class ActionDisplayUneParty extends Action {

    public function execute(): string
    {
        $html = "";
        $pdo = \NRV\Repository\FestivalRepository::makeConnection();
        $id = 0;
        if (isset($_REQUEST["id"])){$id = $_REQUEST["id"];}

        $party = $pdo->getParty();
        $html .= "<br>";
      
        $render = new \NRV\Renderer\PartyRenderer($party);
        $html .= $render->render(2);
        $html .= "<br><br>";
        
        return $html;

    }

}
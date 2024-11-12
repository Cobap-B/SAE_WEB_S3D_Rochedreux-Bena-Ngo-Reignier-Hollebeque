<?php
namespace NRV\Action;

class ActionDisplayParty extends Action {

    public function execute(): string
    {
        $html = "";

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();

            $party = $pdo->displayParty();
            foreach ($party as $h){
                $render = new \NRV\Renderer\PartyRenderer($h);
                $html .= $render->render(2);
                $html .= "<br><br>";
            }
            return $html;

    }

}
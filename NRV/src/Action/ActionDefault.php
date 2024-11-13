<?php
namespace NRV\action;

class ActionDefault extends Action {

    public function execute() {
        $html = "";

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();
        $html .= <<<FIN
                    <div class ="default">
                        <section class ="party">
                            <h2>Party</h2>
        FIN;

        $party = $pdo->displayParty();
        foreach ((Array)$party as $h){
            $render = new \NRV\Renderer\PartyRenderer($h);
            $html .= $render->render(2);
            $html .= "<br><br>";
        }

        $html .= <<<FIN
                        </section>
                        <section class ="show">
                            <h2>Show</h2>
        FIN;

        $shows = $pdo->displayShow("", "", "");
        foreach($shows as $a){
            $render = new \NRV\Renderer\ShowRenderer($a);
            $html .= $render->render(2);
            $html .= "<br><br>";
        }
        $html .= <<<FIN
                        </section>
                    </div>
        FIN;

        return $html;
    }
}

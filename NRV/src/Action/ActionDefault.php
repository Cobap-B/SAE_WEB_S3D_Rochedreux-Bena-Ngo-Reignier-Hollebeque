<?php
namespace NRV\action;

class ActionDefault extends Action {

    public function execute() {
        $html = "";

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();
        $html .= <<<FIN
                    <div class ="default">
                        <a href="">
                        <section class ="party">
                            <h2>Party</h2>
                                <p>
        FIN;

        $party = $pdo->displayParty();
        foreach ((Array)$party as $h){
            $render = new \NRV\Renderer\PartyRenderer($h);
            $html .= $render->render(1);
            $html .= "<br><br>";
        }

        $html .= <<<FIN
                                </p>
                        </section>
                        </a>
                        <a href="">
                        <section class ="show">
                            <h2>Show</h2>
                                <p>
        FIN;

        $shows = $pdo->displayShow("", "", "");
        foreach($shows as $a){
            $render = new \NRV\Renderer\ShowRenderer($a);
            $html .= $render->render(1);
            $html .= "<br><br>";
        }
        $html .= <<<FIN
                                </p>
                        </section>
                        </a>
                    </div>
        FIN;

        return $html;
    }
}

<?php

namespace NRV\acton;

use NRV\Repository\FestivalRepository;
use NRV\Event\Show;
use NRV\Renderer;

class ActionDeleteShow extends Action{

    public function execute(): string{
        if ($this->http_method === 'GET'){
            return <<<END
                    <div>Supprimer un spectacle</div>
                    <form method="POST" action = "?action=del-show">
                    <button type="submit">Supprimer spectacle</button>
                    </form>
            END;
        } else {
            $html = "<div>Effacement d'un spectacle dans le cas POST</div>";

            $r = FestivalRepository::makeConnection();
            $sh = $r->delShow($sh);


            $shR = new Renderer\ShowRenderer($sh);
            $html .= $shR->render(Renderer\Render::COMPACT);
        }

        return $html;
    }
}
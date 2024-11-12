<?php
namespace NRV\Action;
use NRV\Repository\FestivalRepository as FestivalRepository;

class ActionModifyParty extends Action {
    
    public function execute(): string{
        $html = "";
        if ($this->http_method === 'GET'){
            $bd = FestivalRepository::makeConnection();
            $shows = $bd->displayShow("","","");
            $parties = $bd->displayParty();
            $html .= <<<FIN
            <label for="shows">Choose a show:</label>
            <select name="shows" id="shows" form="form">
            FIN;
            foreach($shows as $show){
                $n = $show->name;
                $id = $show->id;
                $html .= <<<FIN
                <option value="$id">$n</option>
                FIN;
            }
            $html .= <<<FIN
            </select>
            <br>
            FIN;

            $html .= <<<FIN
            <label for="parties">Choose a party:</label>
            <select name="parties" id="party" form="form">
            FIN;
            foreach($parties as $party){
                $n = $party->name;
                $id = $party->id;
                $html .= <<<FIN
                <option value="$id">$n</option>
                FIN;
            }
            $html .= <<<FIN
            </select>

            $html .= <<< FIN
            <form method="POST" action="?action=modif-party" id="form">
            <button type="submit" name="valider">Ajouter le spectacle a la soirée</button>
            </form>
            <br>
            FIN;
        }
        else {

        }
        return $html;
    }


}
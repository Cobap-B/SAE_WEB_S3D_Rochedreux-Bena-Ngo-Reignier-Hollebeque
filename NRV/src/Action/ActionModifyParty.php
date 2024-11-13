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
            <br>
            <div>
            <label for="shows">Choose a show (spectacle):</label>
            <br>
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
            </div>
            <br>
            FIN;

            $html .= <<<FIN
            <div>
            <label for="parties">Choose a party (soirée):</label>
            <br>
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
            </div>
            FIN;

            $html .= <<< FIN
            <form method="POST" action="?action=modif-party" id="form">
            <button type="submit" name="valider">Ajouter le spectacle a la soirée</button>
            </form>
            <br>
            FIN;
        }
        else {
            // comparez les dates si vous avez la foi
            $bd = FestivalRepository::makeConnection();
            $txt = $bd->insertPartyToShow($_POST['parties'], $_POST['shows']);
            $html = $txt;
        }
        return $html;
    }


}
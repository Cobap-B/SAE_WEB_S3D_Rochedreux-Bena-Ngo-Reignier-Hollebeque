<?php
namespace NRV\action;

class ActionDisplayShow extends Action {

    public function execute() {
        $html = "";
        $cate = "";
        $date = "";
        $lieu = "";

        $html .= <<<FIN
        <br>
                <br>
        <br>
        <br>
        <br>

            <form method='POST' action='?action=display-show'>
                <fieldset>  
                    <legend>Filtre</legend>
        
                    <fieldset>
                        <legend>Catégorie</legend>
                        <label for='category'>Style de musique :</label>
                        <input type='text' name='category' value='$cate'>
                    </fieldset>
        
                    <fieldset>
                        <legend>Date</legend>
                        <label for='date'>Date :</label>
                        <input type='date' name='date' value='$date'>
                    </fieldset>
        
                    <fieldset>
                        <legend>Lieu</legend>
                        <label for='lieu'>Emplacement :</label>
                        <input type='text' name='lieu' value='$lieu'>
                    </fieldset>
        
                    <input type='submit' name='val' value='Filtrer'>
                </fieldset>
            </form>  
        FIN;

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();
        if ($this->http_method === 'GET'){
            //r
        }elseif ($this->http_method === 'POST'){
            var_dump($_ENV);
            if (isset($_POST["category"])){$cate = $_POST["category"];}
            if (isset($_POST["date"])){$date = $_POST["date"];}
            if (isset($_POST["lieu"])){$lieu = $_POST["lieu"];}
        
            $html.='<div class="conta">';
            $shows = $pdo->displayShow($cate, $date, $lieu);
            foreach($shows as $a){
                $id = $pdo->getIdParty($a->id);
                $render = new \NRV\Renderer\ShowRenderer($a);
                $html .= '<div class="cont">';
                $html .= "<a href='?action=display-une-party?id=$id'>Look party</a>";
                $html .= $render->render(2);
                $html .= "</div>";
                $html .= "<br><br>";
            }
            $html.='</div">';
        }

        return $html;
    }
}
<?php
namespace NRV\action;

class ActionDisplayShow extends Action {

    public function execute() {
        $html = "";
        $cate = "";
        $date = "";
        $lieu = "";

        $html .= <<<FIN
        <hr>
        <form method='POST' '?action=display-show'>
            <fieldset>  
                <legend>Filtre</legend>

                <fieldset>
                    <legend>Category</legend>
                    <p>Style de musique : </p>
                    <input type='text' name='category' value='$cate'>
                </fieldset>

                <fieldset>
                    <legend>Date</legend>
                    <p>Date :</p>
                    <input type='date' name='date' value=$date>
                </fieldset><br>

                <fieldset>
                    <legend>Location</legend>
                    <p>Location :</p>
                    <input type='text' name='lieu' value='$lieu'>
                </fieldset><br>

                <input type='submit' name="val" value='Filtrer'>
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
                $render = new \NRV\Renderer\ShowRenderer($a);
                $html .= '<div class="cont">';
                $html .= $render->render(2);
                $html .= "</div>";
                $html .= "<br><br>";
            }
            $html.='</div">';
        }
       
        return $html;
    }
}
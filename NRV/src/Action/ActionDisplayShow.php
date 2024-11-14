<?php

namespace NRV\action;

class ActionDisplayShow extends Action {

    public function execute() {
        
        $html = "";
        $cate = "";
        $date = "";
        $lieu = "";

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();


        if ($this->http_method === 'POST'){
            //Permet de garder les valeurs
            if (isset($_POST["category"])){$cate = $_POST["category"];}
            if (isset($_POST["date"])){$date = $_POST["date"];}
            if (isset($_POST["lieu"])){$lieu = $_POST["lieu"];}
        }

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
        FIN;
        //Bouton select Categorie
        $pl = $pdo->getCategorie();
        $html .= "Category : <select name='category' size='1' default='$cate'>";
        $html .= "<option value=''>  </option>";
        foreach($pl as $p){
            if ($p == $cate){
                $html .= "<option selected value='$p'> $p </option>";
            }else{
                $html .= "<option value='$p'> $p </option>";
            }
            
        }
        $html .= '</select><br><br>';

        $html .= <<<FIN
                    </fieldset>
        
                    <fieldset>
                        <legend>Date</legend>
                        <label for='date'>Date :</label>
                        <input type='date' name='date' value='$date'>
                    </fieldset>
        
                    <fieldset>
                        <legend>Lieu</legend>
        FIN;
        //Bouton select Location
        $pl = $pdo->getAllLocation();
        $html .= "Lieu : <select name='lieu' size='1' default='$lieu'>";
        $html .= "<option value=''>  </option>";
        foreach($pl as $p){
            $id = $p->__get("id");
            $name = $p->__get("name");
            if ($id == $lieu){
                $html .= "<option selected value='$id'> $name </option>";
            }else{
                $html .= "<option value='$id'> $name </option>";
            }
        }
        $html .= '</select><br><br>';

        $html .= <<<FIN
                    </fieldset>
        
                    <input type='submit' name='val' value='Filtrer'>
                </fieldset>
            </form>  
        FIN;

        
        if ($this->http_method === 'GET'){
            //r
        }elseif ($this->http_method === 'POST'){
        
            $html.='<div class="conta">';
            $shows = $pdo->displayShow($cate, $date, $lieu);
            foreach($shows as $a){
                $id = $pdo->getIdParty($a->id);
                $render = new \NRV\Renderer\ShowRenderer($a);
                $html .= '<div class="cont">';
                $html .= "<a href='?action=display-une-party&id=$id'>Look party</a>";
                $html .= $render->render(2);
                $html .= "</div>";
                $html .= "<br><br>";
            }
            $html.='</div">';
        }

        return $html;
    }
}
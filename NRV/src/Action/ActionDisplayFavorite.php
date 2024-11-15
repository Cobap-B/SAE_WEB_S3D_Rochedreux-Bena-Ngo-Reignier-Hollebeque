<?php

namespace NRV\action;

use NRV\Auth\AuthnProvider;
use NRV\Repository\FestivalRepository;

class ActionDisplayFavorite extends Action{

    public function execute(): string{
        $html = "";

        $pdo = \NRV\Repository\FestivalRepository::makeConnection();

        if (isset($_POST["Favorite"])){
            //AJOUT AU FAVORITE
            if (! isset($_SESSION["Favorite"]) || count($_SESSION["Favorite"])==0){
                $_SESSION["Favorite"] = [];
            }
            if (in_array($_POST["Favorite"], $_SESSION["Favorite"])){
                $i = array_search($_POST["Favorite"], $_SESSION["Favorite"]);
                $pdo->removeFavorite($_SESSION["Favorite"][$i]);
                unset($_SESSION["Favorite"][$i]);
            }else{
                array_push($_SESSION["Favorite"], $_POST["Favorite"]);
                if (isset($_SESSION['user']['id'])){
                    $pdo->saveFavorite($_POST["Favorite"]);
                }
            }
            //unset($_POST["Favorite"]);
            //MARCHE PAS ;(
        }   

        //si l'utilisateur est connecté
        if (isset($_SESSION['Favorite'])) {
            $html.='<br><br><br><br><br><br>';
            if (!isset($_SESSION['user'])){
                $html.='<center><div class="p">
                            Vous devez être connecté pour sauvegarder des favorites
                        </div></center><br>';
            }
            $html.='<div class="conta">';
            $shows = $pdo->displayShow("", "", "");
            foreach($shows as $a){
                if (in_array($a->id, $_SESSION["Favorite"])){
                    $id = $pdo->getIdParty($a->id);
                    $render = new \NRV\Renderer\ShowRenderer($a);
                    $html .= '<div class="cont">';
                    $html .= "<a href='?action=display-une-party&id=$id'>Look party</a>";
                    $html .= "<form class='formLove' method='POST' action='?action=display-favorite'>";
                    
                    $html .= "<input class='love2' type='submit' name='Favorite' value='$a->id'>";
                   
                    $html .= "</form>";
                    $html .= $render->render(2);
                    $html .= "</div>";
                    $html .= "<br><br>";
                }
                
            }
            $html.='</div">';
        }
        return $html;
    }
}
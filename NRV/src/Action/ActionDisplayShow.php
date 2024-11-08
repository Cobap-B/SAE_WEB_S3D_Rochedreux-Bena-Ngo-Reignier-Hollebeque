<?php
namespace NRV\action;

class ActionDisplayShow extends Action {

    public function execute() {
        $html = "";

        if ($this->http_method === 'POST'){
            var_dump($_POST);
            $html = '';
        }
        $html .= <<<FIN
            <hr>
            <form method='POST' '?action=display-show'>
                <fieldset>
                    <legend>Filtre</legend>

                    <fieldset>
                        <legend>Category</legend>
                        <p>Style de musique : </p>
                        <select name="Playlist" size="1">
                            <option value='pop'> Pop </option>
                        </select>
                    </fieldset>

                    <fieldset>
                        <legend>Date</legend>
                        <p>Date :</p>
                        <input type='date' name='date'>
                    </fieldset><br>

                    <fieldset>
                        <legend>Lieu</legend>
                        <p>Lieu :</p>
                        <input type='label' name='lieu'>
                    </fieldset><br>

                    <input type='submit' value='Filtrer'>
                </fieldset>
            </form>  
            
        FIN;
        return $html;
    }
}
<?php
namespace NRV\action;

class ActionDefault extends Action {

    public function execute(): string
    {
        $html = <<<FIN
            <div class="background-container"></div>
            <div class="overlay"></div>
            <div class="welcome-container">
                <section class="welcome-message">
                    <h1>Bienvenue au Festival NRV!</h1>
                    <p>Plongez dans un univers de musique et de découvertes artistiques, Le Festival NRV est disponible prêt de chez vous.</p>
                    <p>Venez vivre des expériences inoubliables, vibrez avec des performances uniques et créez des souvenirs mémorables avec nous au Festivaz NRV !</p>
                </section>
            </div>
            </div>
            </div>
        FIN;
        return $html;
    }
}

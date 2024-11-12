<?php
declare(strict_types=1);
namespace NRV\Renderer;

use NRV\Event\FavoriteList;
use NRV\Event\Show;

class FavoriteListRenderer implements Renderer {

    private FavoriteList $favoriteList;

    public function __construct(FavoriteList $favoriteList) {
        $this->favoriteList = $favoriteList;
    }

    public function render(int $selector = 0): string {
        switch ($selector) {
            case self::COMPACT:
                return $this->renderCompact();
            case self::LONG:
                return $this->renderLong();
            default:
                throw new \InvalidArgumentException("Invalid selector for render type");
        }
    }

    public function renderCompact(): string {
        return sprintf(
            "You have %d shows in your list of liked shows",
            count($this->favoriteList->shows)
        );
    }

    public function renderLong(): string {
        $details = "";
        foreach ($this->favoriteList->shows as $show) {
            $showRenderer = new ShowRenderer($show);
            $details .= $showRenderer->renderLong() . "\n";
        }

        return sprintf(
            "Shows:\n%s",

            $details
        );
    }
}


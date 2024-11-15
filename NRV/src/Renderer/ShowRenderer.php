<?php
declare(strict_types=1);
namespace NRV\Renderer;

use NRV\Event\Show;


class ShowRenderer implements Renderer {

    private Show $show;

    public function __construct(Show $show) {
        $this->show = $show;
    }

    public function render(int $selector = 0): string
    {
        switch ($selector) {
            case self::COMPACT:
                return $this->renderCompact();
            case self::LONG:
                return $this->renderLong();
            default:
                throw new \InvalidArgumentException("Invalid selector, please choose case 1 or 2");
        }
    }

    public function renderCompact(): string {
        return sprintf(
            "%s par %s, Débute le %s",
            $this->show->name,
            $this->show->artist,
            $this->show->dateDebut->format('d-m-y à H:i'),

        );
    }

    public function renderLong(): string {
        return (string)$this->show;
    }
}

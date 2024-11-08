<?php
declare(strict_types=1);
namespace NRV\Renderer;

use NRV\Event\Show;


class ShowRenderer implements Render {

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
            "Show: %s by %s (%d min)",
            $this->show->title,
            $this->show->artist,
            $this->show->duration
        );
    }

    public function renderLong(): string {
        return (string)$this->show;
    }
}

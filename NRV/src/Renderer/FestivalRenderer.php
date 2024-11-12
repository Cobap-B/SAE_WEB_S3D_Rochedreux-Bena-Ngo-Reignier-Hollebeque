<?php
declare(strict_types=1);
namespace NRV\Renderer;

use NRV\Event\Festival;

class FestivalRenderer implements Renderer {

    private Festival $festival;

    public function __construct(Festival $festival) {
        $this->festival = $festival;
    }

    public function render(int $selector = 0): string {
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
            "Festival: %s, Duration: %s, Number of Parties: %d",
            $this->festival->name,
            $this->festival->duration,
            count($this->festival->partys)
        );
    }

    public function renderLong(): string {
        $details = "";
        foreach ($this->festival->partys as $party) {
            $partyRenderer = new PartyRenderer($party);
            $details .= $partyRenderer->renderLong() . "\n";
        }

        return sprintf(
            "Festival: %s\nDuration: %s\nParties:\n%s",
            $this->festival->name,
            $this->festival->duration,
            $details
        );
    }
}


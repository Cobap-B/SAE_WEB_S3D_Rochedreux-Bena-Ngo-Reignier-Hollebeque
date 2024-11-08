<?php
declare(strict_types=1);
namespace NRV\Renderer;

use NRV\Event\Party;

class PartyRenderer implements Render {

    private Party $party;

    public function __construct(Party $party) {
        $this->party = $party;
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
            "%s Party on %s at %d with %d shows)",
            $this->party->name,
            $this->party->date,
            $this->party->place,
            count($this->party->shows)
        );
    }

    public function renderLong(): string {
        $details = "";
        foreach ($this->party->shows as $show) {
            $details .= $show ."\n";
        }

        return sprintf(
            "%s Party\n on %s\nat: %d\nShows:\n%s",
            $this->party->name,
            $this->party->date,
            $this->party->place,
            $details
        );
    }
}

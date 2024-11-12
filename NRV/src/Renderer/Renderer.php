<?php
declare(strict_types=1);
namespace NRV\Renderer;

interface Renderer{

    const COMPACT = 1;
    const LONG = 2;

    public function render(int $selector=0);
    public function renderCompact();
    public function renderLong();
}
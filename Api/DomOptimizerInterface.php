<?php
namespace Creatuity\PageOptimizer\Api;

interface DomOptimizerInterface
{
    public function isEnabled(): bool;

    public function optimize(string &$html): void;
}
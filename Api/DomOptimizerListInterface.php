<?php
namespace Creatuity\PageOptimizer\Api;

interface DomOptimizerListInterface
{
    public function runOptimizers(string &$html): void;
}
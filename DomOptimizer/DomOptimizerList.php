<?php

namespace Creatuity\PageOptimizer\DomOptimizer;

use Creatuity\PageOptimizer\Api\DomOptimizerInterface;
use Creatuity\PageOptimizer\Api\DomOptimizerListInterface;

class DomOptimizerList implements DomOptimizerListInterface
{
    /**
     * @var DomOptimizerInterface[]
     */
    private $optimizers;

    /**
     * Optimizers constructor.
     * @param DomOptimizerInterface[] $optimizers
     */
    public function __construct(array $optimizers)
    {
        $this->optimizers = $optimizers;
    }

    /**
     * @param string $html
     */
    public function runOptimizers(string &$html): void
    {
        foreach ($this->optimizers as $optimizer) {
            if ($optimizer->isEnabled()) {
                $optimizer->optimize($html);
            }
        }
    }
}
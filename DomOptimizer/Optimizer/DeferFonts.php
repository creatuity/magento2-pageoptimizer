<?php
namespace Creatuity\PageOptimizer\DomOptimizer\Optimizer;

use Creatuity\PageOptimizer\Api\DomOptimizerInterface;
use Creatuity\PageOptimizer\Model\Config;

class DeferFonts implements DomOptimizerInterface
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return $this->config->isDeferFontsEnabled();
    }

    public function optimize(string &$html): void
    {
        /*
         * TODO: Add way to defer font requests
         * Example: <link rel="preload" href="/fonts/example.woff2" as="font">
         */
    }
}
<?php
namespace Creatuity\PageOptimizer\DomOptimizer\Optimizer;

use Creatuity\PageOptimizer\Api\DomOptimizerInterface;
use Creatuity\PageOptimizer\Model\Config;

class DeferJs implements DomOptimizerInterface
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return $this->config->isDeferJsEnabled();
    }

    public function optimize(string &$html): void
    {
        $tags = $this->getJsTags($html);
        $this->removeOldJsTags($html);
        $this->addNewJsTags($html, $tags);
    }

    private function getConditionJsRegex(): string
    {
        return '@(?:<script type="text/javascript"|<script)(.*)</script>@msU';
    }

    private function getJsTags(string &$html): array
    {
        preg_match_all($this->getConditionJsRegex(), $html, $matches);
        return $matches[0];
    }

    private function removeOldJsTags(string &$html): void
    {
        $html = preg_replace($this->getConditionJsRegex(), '', $html);
    }

    private function addNewJsTags(string &$html, array $tags): void
    {
        $html = str_replace('</body>', implode('', $tags) . '</body>', $html);
    }
}
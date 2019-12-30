<?php

namespace Creatuity\PageOptimizer\DomOptimizer\Optimizer;

use Creatuity\PageOptimizer\Api\DomOptimizerInterface;
use Creatuity\PageOptimizer\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class PreconnectExternalUrls implements DomOptimizerInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    private $config;

    public function __construct(StoreManagerInterface $storeManager, Config $config)
    {
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return $this->config->isPreconnectExternalUrlsEnabled();
    }

    /**
     * @param $html
     * @throws NoSuchEntityException
     */
    public function optimize(string &$html): void
    {
        $find = preg_match_all('/<link.*rel="([^"]+?)".*>/', $html, $matches);

        if ($find === false) {
            return;
        }

        foreach ($matches[0] as $i => $linkTag) {
            $this->replaceLinkTag($html, $linkTag, $matches[1][$i]);
        }
    }

    /**
     * @param $html
     * @param string $linkTag
     * @param string $rel
     * @throws NoSuchEntityException
     */
    private function replaceLinkTag(&$html, string $linkTag, string $rel): void
    {
        $findHref = preg_match('/href="(.*)"/', $linkTag, $hrefMatch);
        if ($findHref <= 0) {
            return;
        }
        $href = $hrefMatch[1];
        if (strpos($href, $this->getStoreDomain()) !== false) {
            return;
        }
        $newLinkTag = str_replace('"' . $rel, '"' . $rel . ' preconnect', $linkTag);
        $html = str_replace($linkTag, $newLinkTag, $html);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getStoreDomain(): string
    {
        return parse_url($this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB))['host'];
    }
}
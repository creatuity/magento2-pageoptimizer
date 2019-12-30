<?php
namespace Creatuity\PageOptimizer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const ENABLE_DEFER_JS = 'creatuity_pageoptimizer/general/enable_defer_js';
    public const ENABLE_DEFER_CSS = 'creatuity_pageoptimizer/general/enable_defer_css';
    public const ENABLE_DEFER_FONTS = 'creatuity_pageoptimizer/general/enable_defer_fonts';
    public const ENABLE_PRECONNECT_EXTERNAL_URLS = 'creatuity_pageoptimizer/general/enable_preconnect_external_urls';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isDeferJsEnabled($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_DEFER_JS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isDeferCssEnabled($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_DEFER_CSS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isDeferFontsEnabled($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_DEFER_FONTS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isPreconnectExternalUrlsEnabled($storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_PRECONNECT_EXTERNAL_URLS, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
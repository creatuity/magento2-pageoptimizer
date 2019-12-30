<?php

namespace Creatuity\PageOptimizer\DomOptimizer\Optimizer;

use Creatuity\PageOptimizer\Api\DomOptimizerInterface;
use Creatuity\PageOptimizer\Model\Config;

class DeferCss implements DomOptimizerInterface
{
    public const LOADCSS_SCRIPT = <<<JS
/*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
!function(e){"use strict";var t=function(t,n,r,o){var i,a=e.document,d=a.createElement("link");if(n)i=n;else{var f=(a.body||a.getElementsByTagName("head")[0]).childNodes;i=f[f.length-1]}var l=a.styleSheets;if(o)for(var s in o)o.hasOwnProperty(s)&&d.setAttribute(s,o[s]);d.rel="stylesheet",d.href=t,d.media="only x",function e(t){if(a.body)return t();setTimeout(function(){e(t)})}(function(){i.parentNode.insertBefore(d,n?i:i.nextSibling)});var u=function(e){for(var t=d.href,n=l.length;n--;)if(l[n].href===t)return e();setTimeout(function(){u(e)})};function c(){d.addEventListener&&d.removeEventListener("load",c),d.media=r||"all"}return d.addEventListener&&d.addEventListener("load",c),d.onloadcssdefined=u,u(c),d};"undefined"!=typeof exports?exports.loadCSS=t:e.loadCSS=t}("undefined"!=typeof global?global:this);
JS;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isEnabled(): bool
    {
        return $this->config->isDeferCssEnabled();
    }

    public function optimize(string &$html): void
    {
        $knownCssUrls = $this->getKnownCssUrls($html);
        $this->removeOldCssTags($html);
        $this->addLoadCssDeclaration($html, $knownCssUrls);
        $this->addCssFallbackViaNoScript($html, $knownCssUrls);
    }

    private function getKnownCssUrls(string $html)
    {
        $find = preg_match_all('/href="([^"]+?\.css)"/', $html, $matches);
        return $find !== false ? $matches[1] : [];
    }

    private function removeOldCssTags(string &$html): void
    {
        $html = preg_replace('/<link (.*)rel="stylesheet"(.*)>/im', '', $html);
    }

    private function addLoadCssDeclaration(string &$html, array $knownCssUrls): void
    {
        $functionCall = '';

        foreach ($knownCssUrls as $cssUrl) {
            $functionCall .= 'loadCSS("' . $cssUrl . '", document.getElementById("loadcss"));' . PHP_EOL;
        }

        $html = str_replace('</head>', '<script id="loadcss">' . self::LOADCSS_SCRIPT . $functionCall . '</script>' . '</head>', $html);
    }

    private function addCssFallbackViaNoScript(string &$html, array $knownCssUrls): void
    {
        $css = '';
        foreach ($knownCssUrls as $cssUrl) {
            $css .= '<link rel="stylesheet" href="' . $cssUrl . '">' . PHP_EOL;
        }
        $html = str_replace('</body>', '<noscript>' . $css . '</noscript>' . '</body>', $html);
    }
}
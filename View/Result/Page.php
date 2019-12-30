<?php

namespace Creatuity\PageOptimizer\View\Result;

use Creatuity\PageOptimizer\DomOptimizer\DomOptimizerList;
use Exception;
use Magento\Framework;
use Magento\Framework\View;

class Page extends View\Result\Page
{
    /**
     * @var DomOptimizerList
     */
    private $domOptimizerList;

    public function __construct(
        View\Element\Template\Context $context,
        View\LayoutFactory $layoutFactory,
        View\Layout\ReaderPool $layoutReaderPool,
        Framework\Translate\InlineInterface $translateInline,
        View\Layout\BuilderFactory $layoutBuilderFactory,
        View\Layout\GeneratorPool $generatorPool,
        View\Page\Config\RendererFactory $pageConfigRendererFactory,
        View\Page\Layout\Reader $pageLayoutReader,
        $template,
        DomOptimizerList $domOptimizerList,
        $isIsolated = false,
        View\EntitySpecificHandlesList $entitySpecificHandlesList = null
    ) {
        parent::__construct($context,
            $layoutFactory,
            $layoutReaderPool,
            $translateInline,
            $layoutBuilderFactory,
            $generatorPool,
            $pageConfigRendererFactory,
            $pageLayoutReader,
            $template,
            $isIsolated,
            $entitySpecificHandlesList
        );
        $this->domOptimizerList = $domOptimizerList;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function renderPage(): string
    {
        $output = parent::renderPage();
        $this->domOptimizerList->runOptimizers($output);
        return $output;
    }
}
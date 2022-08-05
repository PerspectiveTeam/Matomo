<?php

namespace Perspective\Matomo\Block\Customer\Account;

use Magento\Framework\View\Element\Template;
use Perspective\Matomo\Helper\Config\General;

class DealsMain extends Template implements \Magento\Framework\View\Element\BlockInterface
{
    /**
     * @var \Perspective\Matomo\Helper\Config\General
     */
    private $generalConfig;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Perspective\Matomo\Helper\Config\General $generalConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        General $generalConfig,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->generalConfig = $generalConfig;
    }

    public function getRefreshUrl()
    {
        return $this->getUrl( $this->generalConfig->getRouterPath());
    }

}

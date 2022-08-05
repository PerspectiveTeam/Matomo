<?php
namespace Perspective\Matomo\Controller;

use Perspective\Matomo\Helper\Config\General;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Router\ActionList;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ActionList
     */
    private $actionList;

    /**
     * @var General
     */
    private $generalConfig;

    /**
     * @param ActionFactory $actionFactory
     * @param ActionList $actionList
     * @param General $generalConfig
     */
    public function __construct(
        ActionFactory $actionFactory,
        ActionList $actionList,
        General $generalConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->actionList = $actionList;
        $this->generalConfig = $generalConfig;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        if (!$this->generalConfig->isEnabled()){
            return null;
        }
        $identifier = trim($request->getPathInfo(), '/');
        if ($identifier !== $this->generalConfig->getRouterPath()) {
            return null;
        }

        $actionClassName = $this->actionList->get('Perspective_Matomo', null, 'index', 'refresh');
        return $this->actionFactory->create($actionClassName);
    }
}

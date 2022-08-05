<?php

declare(strict_types=1);

namespace Perspective\Matomo\Controller\Index;

use Exception;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Perspective\Matomo\Helper\Config\General;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Perspective\Matomo\Service\ProcessRefresh;

/**
 * Processes request to custom controller and decide type of result
 */
class Refresh extends Action implements CsrfAwareActionInterface, HttpPostActionInterface
{
    /**
     * @var General
     */
    private $generalConfig;

    /**
     * @var \Perspective\Matomo\Service\ProcessRefresh
     */
    private $refreshService;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Perspective\Matomo\Helper\Config\General $generalConfig
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        General $generalConfig,
        ProcessRefresh $refreshService
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->generalConfig = $generalConfig;
        $this->refreshService = $refreshService;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        if (!$this->generalConfig->isEnabled()) {
            $result->setData([
                'success' => true,
                'error' => __('Matomo is disabled.')
            ]);
            return $result;
        }
        try {
            $response = $this->refreshService->process($this->getRequest());
            $result->setData(['success' => true,
                'response' => $response]);
        } catch (Exception $e) {
            $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}

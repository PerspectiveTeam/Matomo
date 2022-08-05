<?php

namespace Perspective\Matomo\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class EventTypes implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'eventCategory', 'label' => __('eventCategory')],
            ['value' => 'eventAction', 'label' => __('eventAction')],
            ['value' => 'eventName', 'label' => __('eventName')]
        ];
    }
}

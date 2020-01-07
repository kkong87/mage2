<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Ui\Controller\Adminhtml\Index;

use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Ui\Controller\Adminhtml\AbstractAction;
use Magento\Framework\View\Element\UiComponentInterface;

/**
 * Class Render
 */
class Render extends AbstractAction
{
    /**
     * Action for AJAX request
     *
     * @return void
     */
    public function execute()
    {
        $component = $this->factory->create($this->_request->getParam('namespace'));
        $aclResource = $component->getData('acl');

        if ($aclResource && !$this->_authorization->isAllowed($aclResource)) {
            $this->_redirect('admin/noroute');
            return;
        }

        $this->prepareComponent($component);

        if ($component->getContext()->getAcceptType() == 'json') {
            $this->_response->setHeader('Content-Type', 'application/json');
        }

        $this->_response->appendBody((string) $component->render());
    }

    /**
     * Call prepare method in the component UI
     *
     * @param UiComponentInterface $component
     * @return void
     */
    protected function prepareComponent(UiComponentInterface $component)
    {
        foreach ($component->getChildComponents() as $child) {
            $this->prepareComponent($child);
        }
        $component->prepare();
    }
}

<?php

/**
 * Copyright 2024 Sanjeev Kumar
 * NOTICE OF LICENSE
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Sanjeev
 * @package   Sanjeev_SpamContentBlocker
 * @copyright Copyright (c) 2024
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Sanjeev\SpamContentBlocker\Plugin;


class DispatchAction
{

    /**
     * @var \Sanjeev\SpamContentBlocker\Helper\Data
     */
    protected $helper;

    /**
     * CustomHeader constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Sanjeev\SpamContentBlocker\Helper\Data $helper
        )
    {
        $this->helper = $helper;
    }


    public function beforeDispatch($subject, $args)
    {
        $input = $this->helper->getContent();

        if(preg_match('/addafterfiltercallback/si', preg_replace("/[^A-Za-z]/", '', urldecode(urldecode($input))))) {
            $this->sendTemporarilyUnavailableResponse();
        }

        if (strpos($input, 'dataIsURL') !== false) {
            $this->sendTemporarilyUnavailableResponse();
        }

        if ($this->helper->isIPAddressBlocked()) {
            $this->sendTemporarilyUnavailableResponse();
        }

        if ($this->helper->isEmailDomainBlocked()) {
            $this->sendTemporarilyUnavailableResponse();
        }

        if ($this->helper->isEmailBlocked()) {
            $this->sendTemporarilyUnavailableResponse();
        }

        if ($this->helper->isUserAgentBlocked()) {
            $this->sendTemporarilyUnavailableResponse();
        }

        return [$args];
    }

    protected function sendTemporarilyUnavailableResponse()
    {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        exit;
    }
}

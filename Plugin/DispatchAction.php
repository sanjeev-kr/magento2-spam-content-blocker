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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CustomHeader constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        )
    {
        $this->scopeConfig = $scopeConfig;
    }


    public function beforeDispatch($subject, $args)
    {   
        $input = file_get_contents('php://input');
        $input = preg_replace("/\\\\0/", '',$input);
        $input = preg_replace("/\\\\n/", '',$input);
        $input = preg_replace("/\\\\t/", '',$input);
        if(preg_match('/addafterfiltercallback/si', preg_replace("/[^A-Za-z]/", '', urldecode(urldecode($input))))) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            exit;
        }

        if (strpos(file_get_contents('php://input'), 'dataIsURL') !== false) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            exit;
        }
        return [$args];
    }
}

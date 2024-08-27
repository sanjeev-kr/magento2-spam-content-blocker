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


use Magento\Framework\Component\ComponentRegistrar as ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, "Sanjeev_SpamContentBlocker",__DIR__);

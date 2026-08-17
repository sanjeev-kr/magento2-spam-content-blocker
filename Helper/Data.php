<?php
namespace Sanjeev\SpamContentBlocker\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $serialize;

    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->serialize = $serialize;
        $this->storeManager = $storeManager;
    }

    public function getConfigValue($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getConfigData($field)
    {
        return $this->getConfigValue('spam_blocker/general/'.$field, $this->storeManager->getStore()->getId());
    }

    public function getBlockedEmails()
    {
        return (string) $this->getConfigData('block_emails');
    }

    public function getBlockedEmailDomains()
    {
        return (string) $this->getConfigData('block_email_domains');
    }

    public function isEmailBlocked()
    {
        $blockedList = $this->getBlockedEmails();
        if(empty($blockedList)) {
            return false;
        }

        $input = $this->getContent();
        $blockeds = $this->convertCommanSeparatedToArray($blockedList);
        foreach($blockeds as $blocked) {
            if(stripos($input, $blocked) !== false ){
                return true;
            }
        }
        return false;
    }

    public function isEmailDomainBlocked()
    {
        $blockedList = $this->getBlockedEmailDomains();
        if(empty($blockedList)) {
            return false;
        }

        $blockeds = $this->convertCommanSeparatedToArray($blockedList);
        $input = $this->getContent();
        foreach($blockeds as $blocked) {
            if(stripos($input, $blocked) !== false ){
                return true;
            }
        }

        return false;
    }

    public function getBlockedUserAgents()
    {
        return (string) $this->getConfigData('blocked_useragent');
    }

    public function isUserAgentBlocked()
    {
        $blockedUserAgents = $this->getBlockedUserAgents();
        if(empty($blockedUserAgents)) {
            return false;
        }

        $request = $this->_getRequest();
        $userAgent = $request->getServer('HTTP_USER_AGENT');
        $blockedUserAgents = str_replace("\r\n","\n", $blockedUserAgents);
        $userAgents = explode("\n", $blockedUserAgents);
        $filteredUAs = array_filter($userAgents,"trim");

        foreach($filteredUAs as $filteredUA) {
            if(preg_match("/{$filteredUA}/i", $userAgent)){
                return true;
            }
        }

        return false;
    }

    public function getBlockedIPAddresses()
    {
        return (string) $this->getConfigData('blocked_ips');
    }
    
    public function isIPAddressBlocked() 
    {
        $ipAddress = $this->getClientIp();
        if(empty($ipAddress)) {
            return false;
        }
        $ipsList = $this->getBlockedIPAddresses();
        $filteredIPs = $this->convertCommanSeparatedToArray($ipsList);

        if(in_array($ipAddress, $filteredIPs)){
            return true;
        }

        return false;
    }


    public function getClientIp() {

        $request = $this->_getRequest();
        $ipAddress = null;
        $httpClientIp = $request->getServer('HTTP_CLIENT_IP');
        $proxyIp = $request->getServer('HTTP_X_FORWARDED_FOR');
        $remoteIp = $request->getServer('REMOTE_ADDR');
        if(!empty($httpClientIp)){
            $ipAddress = $httpClientIp;
        } elseif (!empty($proxyIp)){
            $ipAddress = $proxyIp;
        } else {
            $ipAddress = $remoteIp;
        }
        return $ipAddress;
    }

    protected function convertCommanSeparatedToArray($content)
    {
        $contentArray = explode(",", $content);
        $arr = array_filter($contentArray,"trim");
        return $arr;
    }

    public function getContent()
    {
        $input = file_get_contents('php://input');
        if (empty($input)){
            $request = $this->_getRequest();
            preg_match('/boundary=(.*)$/', $request->getServer('CONTENT_TYPE'), $matches);
            $boundary = $matches[1] ?? null;
            if($boundary){
                $blocks = $request->getParams();
                $input = json_encode($blocks);
            }
        }
        $input = preg_replace("/\\\\0/", '',$input);
        $input = preg_replace("/\\\\n/", '',$input);
        $input = preg_replace("/\\\\t/", '',$input);
        return $input;
    }

}

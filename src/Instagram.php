<?php

namespace am\instagram;

use am\object\Object;

/**
 * It will use the instagram web to provide simple features
 *
 * @author Saeed johari
 */
class Instagram
{
    /**
     * Instagram web url
     */  
    const URL = 'https://www.instagram.com/%s/?__a=1';
    
    /**
     * Default user agent
     */
    const AGENT = 'Mozilla/5.0 (X11; Linux x86_64; rv:57.0) Gecko/20100101 Firefox/57.0';
    
    /**
     * Request url
     */
    protected $url;
    
    /**
     * Http proxy
     */
    protected $proxy;
    
    /**
     * Set request url
     */
    public function setUrl($url)
    {
        $this->url = $url;   
    }
    
    /**
     * Get request url
     */
    public function getUrl()
    {
        return $this->url;   
    }
    
    /**
     * Set http proxy
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;   
    }
    
    /**
     * Get http proxy
     */
    public function getProxy()
    {
        return $this->proxy;   
    }
    
    /**
     * Get user informations
     */
    public function getUser($name)
    {
        $this->setUrl($name);
        
        $result = $this->getResult();
        
        if (empty($result->user)) {
            return false;   
        }
        
        return new Object($result->user);
    }
    
    /**
     * Get full request url
     */
    protected function getFullUrl()
    {
        $url = sprintf(self::URL, $this->getUrl());
        
        return $url;
    }
    
    /**
     * Send request to the instagram web
     */
    protected function getResult()
    {
        $curl = curl_init($this->getFullUrl());
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        if ($proxy = $this->getProxy()) {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
        }
        
        curl_setopt($curl, CURLOPT_USERAGENT, self::AGENT);
        
        $result = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($result);
    }
}
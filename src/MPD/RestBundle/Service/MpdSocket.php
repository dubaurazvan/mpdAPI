<?php

namespace MPD\RestBundle\Service;

/**
 * Class Documentation
 * 
 * @package   MPD\RestBundle\Service
 * @author    Razvan Dubau <dubau_razvan@yahoo.com>
 * @copyright Copyright (c) 2014 Extragsm (http://www.extragsm.com/)
 */
class MpdSocket
{
    const MPD_RESPONSE_OK = 'OK';
    const MPD_RESPONSE_ERR = 'ACK';
    
    /**
     * @var string
     */
    protected $errStr;
    
    protected $connected = false;
    
    protected $mpdSocket = false;
    
    /**
     * Check if script is connect to MPD Socket
     * 
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }
    
    /**
     * Connect to MPD Socket
     * 
     * @param type $host
     * @param type $port
     * @param type $password
     * @return boolean|null
     */
    public function connect($host = 'localhost', $port = 6600, $password = NULL)
    {
        $this->mpdSocket = fsockopen($host, $port, $errNo, $errStr, 10);
        
        if (!$this->mpdSocket) {
            return $this->setError("Socket Error: $errStr ($errNo)")->getError();
        } else {
            while(!feof($this->mpdSocket)) {
                $response =  fgets($this->mpdSocket,1024);
                
                if (strncmp(self::MPD_RESPONSE_OK, $response, strlen(self::MPD_RESPONSE_OK)) == 0) {
                    $this->connected = true;
                    return true;
                }
                
                if (strncmp(MPD_RESPONSE_ERR, $response,strlen(MPD_RESPONSE_ERR)) == 0) {
                    $this->setError("Server responded with: $response");
                    return null;
                }
            }
            
            if (!$this->isValid()) {
                return $this->getError();
            }
        }
    }
    
    /**
     * Execute a command against mpd service
     * 
     * @param type $cmdStr
     * @param type $arg1
     * @param type $arg2
     * @return array
     */
    public function execute($cmdStr, $arg1 = '', $arg2 = '')
    {
        if (!$this->isConnected()) {
            return $this->setError('Not connected')->getError();
        } else {
            // Clear out the error String
            $this->errString = "";
            $respStr = "";

            $cmdStr .= !empty($arg1) ? : '';
            $cmdStr .= !empty($arg2) ? : '';
            
            fputs($this->mpdSocket, "$cmdStr\n");

            while(!feof($this->mpdSocket)) {
                $response = fgets($this->mpdSocket,1024);

                // Build the response string
                $respStr .= $response;
                
                if ($this->validate($response)) {
                    break;
                }
                
                if (false === $this->validate($respStr)) {
                    return null;
                }
            }
            
            if (!$this->isValid()) {
                return $this->getError();
            }
        }
        
        $respStr = explode("\n", trim($respStr));
        
        return $respStr;
    }
    
    /**
     * Validate a response
     * This method is called in recursive execution
     * If an error will occur, setError will add it to the list of errors.
     * 
     * @param type $response
     * @return boolean|null
     */
    protected function validate($response)
    {
        if (strncmp(self::MPD_RESPONSE_OK, $response, strlen(self::MPD_RESPONSE_OK)) == 0) {
           return true;
        }
        
        // An ERR signals the end of transmission with an error! Let's grab the single-line message.
        if (strncmp(self::MPD_RESPONSE_ERR, $response, strlen(self::MPD_RESPONSE_ERR)) == 0) {
            list($junk, $errTmp) = explode(self::MPD_RESPONSE_ERR . ' ', $response);
            $this->setError(strtok($errTmp,"\n"));
            
            return null;
        }
    }
    
    /**
     * Check if there is any error stored after a request
     * 
     * @return boolean
     */
    protected function isValid()
    {
        if (!empty($this->errorString)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set an error
     * 
     * @param mixed $error
     * @return \MPD\RestBundle\Service\MpdSocket
     */
    protected function setError($error)
    {
        $this->errorString = $error;
        
        return $this;
    }
    
    /**
     * Get errors
     * 
     * @return array
     */
    protected function getError()
    {
        return array('error' => $this->errorString);
    }
    
    /**
     * Get MPD status. 
     * It will an array of infos about the current status of MPD
     * 
     * @return type
     */
    public function getStatus()
    {
        $response = $this->execute('status');
        array_pop($response);
        
        $return = array();
        foreach ($response as $item) {
            list($key, $value) = explode(':', $item);
            $return[trim($key)] = trim($value);
        }
        
        return $return;
    }
}
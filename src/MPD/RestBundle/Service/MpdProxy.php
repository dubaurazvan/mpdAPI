<?php

namespace MPD\RestBundle\Service;

/**
 * Class Documentation
 * 
 * @package   MPD\Restbundle\Service
 * @author    Razvan Dubau <dubau_razvan@yahoo.com>
 * @copyright Copyright (c) 2014 Extragsm (http://www.extragsm.com/)
 */
class MpdProxy
{
    public function __construct()
    {
        $this->connect();
    }
    
    private function connect()
    {
        // $mpd = new mpd($mpdServer, $mpdPort, $mpdPassword);
    }
    
    public function play()
    {
        $this->socket->Play();
    }
}
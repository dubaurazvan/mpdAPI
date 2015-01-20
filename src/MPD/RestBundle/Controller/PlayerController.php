<?php

namespace MPD\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * Rest controller for Player
 *
 * @package MPD\RestBundle\Controller
 * @author Dubau Razvan <dubau_razvan@yahoo.com>
 */
class PlayerController extends FOSRestController
{
    /**
     * @View(templateVar="output")
     * @GET("/play")
     */
    public function playAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->execute("play");
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/stop")
     */
    public function stopAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->execute("stop");
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/pause")
     */
    public function pauseAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->execute("pause");
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/next")
     */
    public function nextAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->execute("next");
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/previous")
     */
    public function previousAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->execute("previous");
    }
    
    /**
     * Seek the currently playing song
     * 
     * @View(templateVar="output")
     * @GET("/seek/{time}")
     */
    public function seekAction($time)
    {
        $mpd = $this->get('mpd_socket');
        
        $status = $mpd->getStatus();

        return $mpd->execute('seekid ' . $status['songid']  . ' ' . $time);
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/status")
     */
    public function statusAction()
    {
        $mpd = $this->get('mpd_socket');

        return $mpd->getStatus();
    }
}
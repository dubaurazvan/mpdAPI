<?php

namespace MPD\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use \Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * Rest controller for settings
 *
 * @package MPD\RestBundle\Controller
 * @author Dubau Razvan <dubau_razvan@yahoo.com>
 */
class SettingsController extends FOSRestController
{
    /**
     * @View(templateVar="output")
     * @GET("/volume/{volume}")
     */
    public function volumeAction($volume)
    {
        $volume = (int) $volume;
        
        if ($volume > 100 || $volume < 0) {
            throw new InvalidOptionsException("Volume value must be between 0 and 100!");
        }
        
        $mpd = $this->get('mpd_socket');

        return $mpd->execute('setvol', $volume);
    }
    
}
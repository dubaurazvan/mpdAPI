<?php

namespace MPD\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use \Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * Rest controller for browsing the fileSystem
 *
 * @package MPD\RestBundle\Controller
 * @author Dubau Razvan <dubau_razvan@yahoo.com>
 */
class FileSystemController extends FOSRestController
{
    /**
     * @View(templateVar="output")
     * @GET("/file-system")
     */
    public function getAction(Request $request)
    {
        $folder = $request->get('folder', null);
        
        if (!is_null($folder)) {
            print_r($folder);
        }
        
        $mpd = $this->get('mpd_socket');
        
        return $mpd->getFileSystemList();
    }
    
}
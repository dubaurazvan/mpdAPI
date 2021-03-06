<?php

namespace MPD\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;

/**
 * Rest controller for Track
 *
 * @package MPD\RestBundle\Controller
 * @author Dubau Razvan <dubau_razvan@yahoo.com>
 */
class PlaylistController extends FOSRestController
{
    /**
     * @View(templateVar="output")
     * @Post("/playlist/add")
     */
    public function addAction(Request $request)
    {
        $track = $request->get('track');
        $mpd = $this->get('mpd_socket');
        
        return $mpd->addToPlaylist($track);
    }
    
    /**
     * @View(templateVar="output")
     * @Delete("/playlist/remove/{id}")
     */
    public function removeAction($id)
    {
        $mpd = $this->get('mpd_socket');
        
        return $mpd->removeFromPlaylist($id);
    }
    
    /**
     * @View(templateVar="output")
     * @Get("/playlist/move/{id}/{position}")
     */
    public function moveAction($id, $position)
    {
        $mpd = $this->get('mpd_socket');
        
        return $mpd->moveTrack($id, $position);
    }
    
    /**
     * @View(templateVar="output")
     * @GET("/playlist")
     */
    public function listAction()
    {
        $mpd = $this->get('mpd_socket');
        
        return $mpd->getPlaylist();
    }
    
}
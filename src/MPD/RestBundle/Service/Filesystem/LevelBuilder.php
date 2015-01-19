<?php

namespace MPD\RestBundle\Service;

use MPD\RestBundle\Entity\File;
use MPD\RestBundle\Entity\Directory;

/**
 * Tree builder
 * Create a multidimensional array from a flat one. 
 * Ex: MPD FileSystem (mpd listallinfo) return a flat array
 * 
 * @package   MPD\RestBundle\Service
 * @author    Razvan Dubau <dubau_razvan@yahoo.com>
 * @copyright Copyright (c) 2014 Extragsm (http://www.extragsm.com/)
 */
class LevelBuilder
{
    /**
     * @var array
     */
    protected $queue = array(
        'files' => array(), 
        'directories' => array()
    );
    
    /**
     * Build multidimensional array from a flat one
     * 
     * @param array $files  All files registred to MPD database
     * @param string $from  Specify with dir path to search
     * @return array
     */
    public function getItems($items, $from = '')
    {
        $from = str_replace('/', '\/', trim($from, "/"));
        
        $this->getFiles($items, $from);
        
        $this->getDirectories($items, $from);
    
        return $this->queue;
    }
    
    protected function add($item)
    {
        if ($item instanceof Directory) {
            $this->queue['directories'][$item->getName()] = $item;
            return;
        }
        
        $this->queue['files'][] = $item;
    }
    
    protected function getFiles(array $items, $from = '')
    {
        foreach ($items as $item) {
            $file = new File($item);
            
            if (preg_match('/^' . $from . '$/', $file->getPath())) {
                $this->add($file);
            }
        }
    }
    
    protected function getDirectories(array $items, $from = '')
    {
        foreach ($items as $item) {
            $item = new File($item);
            
            // Get only the current level of folders
            if (preg_match("[\/{1}]", $item->getPath())) {
                $dirName = explode('/', $item->getPath());
                $directory = new Directory($dirName[0], $from);
                $this->add($directory);
            }
        }
    }
    
}
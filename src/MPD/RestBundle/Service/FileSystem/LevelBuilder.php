<?php

namespace MPD\RestBundle\Service\FileSystem;

use MPD\RestBundle\Entity\File;
use MPD\RestBundle\Entity\Directory;

/**
 * Level builder
 * Create a flat array of files and directories
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
        'directories' => array(),
        'files' => array()
    );
    
    /**
     * Build the list of directories and files
     * 
     * @param array $files  All files registred to MPD database
     * @param string $root  Specify with dir path to search
     * @return array
     */
    public function getItems($items, $root = '')
    {
        $root = str_replace('/', '\/', trim($root, "/"));
        
        $this->getFiles($items, $root);
        
        $this->getDirectories($items, $root);
    
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
    
    protected function getFiles(array $items, $root = '')
    {
        foreach ($items as $item) {
            $file = new File($item);
            
            if (preg_match('/^' . $root . '$/', $file->getPath())) {
                $this->add($file);
            }
        }
    }
    
    protected function getDirectories(array $items, $root = '')
    {
        foreach ($items as $item) {
            if (preg_match('/' . $root . '/', $item['file'])) {
                $item['file'] = preg_replace("/^{$root}\/(.*)$/", '$1', $item['file']);
                
                if (preg_match('/\//', $item['file'])) {
                    $dirName = explode('/', $item['file']);
                
                    $directory = new Directory($dirName[0], $root);
                    $this->add($directory);
                }
                
            }
        }
    }
    
}
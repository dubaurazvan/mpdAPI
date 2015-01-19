<?php

namespace MPD\RestBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Entity to handle files from MPD file system
 * 
 * @package   MPD\RestBundle\Service
 * @author    Razvan Dubau <dubau_razvan@yahoo.com>
 * @copyright Copyright (c) 2014 Extragsm (http://www.extragsm.com/)
 * 
 * @ExclusionPolicy("all")
 */
class File
{
    /**
     * @var string
     * 
     * @Expose
     */
    protected $name;
    
    /**
     * @var string
     * 
     * @Expose
     */
    protected $path;
    
    /**
     * @var integer
     * 
     * @Expose
     */
    protected $time;
    
    /**
     * Constructor
     * 
     * @param array $path
     * @throws \Exception
     */
    public function __construct(array $file = array())
    {
        if (!count($file)) {
            throw new \Exception("Empty path given!");
        }
        
        $name = explode('/', $file['file']);
        
        $this->setName(end($name));
        
        if (count($name) > 1) {
            // add path
            array_pop($name);
            $path = implode('/', $name);
            $this->setPath($path);
        }
        
        if (isset($file['time'])) {
            $this->setTime($file['time']);
        }
    }
    
    /**
     * Set parent directories (file path)
     * 
     * @param string $path
     * @return \MPD\RestBundle\Entity\File
     */
    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * Get file path
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Get file path
     * 
     * @return string
     * 
     * @VirtualProperty
     */
    public function getFullpath()
    {
        return (!empty($this->path) ? $this->path . '/' : '') . $this->name;
    }
    
    /**
     * Set name
     * 
     * @param type $name
     * @return \MPD\RestBundle\Entity\File
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set time
     * 
     * @param type $time
     * @return \MPD\RestBundle\Entity\File
     */
    public function setTime($time)
    {
        $this->time = $time;
        
        return $this;
    }
    
    /**
     * Get time
     * 
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }
}

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
class Directory
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
     * Constructor
     * 
     * @param array $filepath
     * @throws \Exception
     */
    public function __construct($name, $path = null)
    {
        if (empty($name)) {
            throw new \Exception("No directory information provided!");
        }
        
        $this->setName($name);
        $this->setPath($path);
    }
    
    /**
     * Set directory path
     * 
     * @param string $path
     * @return \MPD\RestBundle\Entity\Directory
     */
    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * Get directory path
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Get directory full path
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
     * Set directory name
     * 
     * @param type $name
     * @return \MPD\RestBundle\Entity\Directory
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get directory name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

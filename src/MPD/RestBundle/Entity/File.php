<?php

namespace MPD\RestBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

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
     * @var string
     */
    protected $lastModified;
    
    /**
     * @var integer 
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
        if (count($name) > 1) {
            $this->setName(end($name));
            
            // add path
            array_pop($name);
            $path = implode('/', $name);
            $this->setPath($path);
        } else {
            $this->setName($name);
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

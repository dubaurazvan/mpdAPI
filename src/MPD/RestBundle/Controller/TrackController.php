<?php

namespace MPD\RestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use Elastica\Query as ElasticaQuery;
use Elastica\Query\QueryString as ElasticaQueryString;

/**
 * Rest controller for search
 *
 * @package Gorkana\RestBundle\Controller
 * @author Dubau Razvan <dubau_razvan@yahoo.com>
 */
class TrackController extends FOSRestController
{
    /**
     * @View(templateVar="output")
     * @GET("/play")
     */
    public function postSearchAutocompleteAction(Request $request)
    {
        $query = $request->get('query', false);
        $limit = $request->get('limit', 3);
        
        if (!$query) {
            throw new \InvalidArgumentException("Field 'query' is empty");
        }
        
        if ($limit > 10) {
            throw new \InvalidArgumentException("Invalid limit value! Max. limit is 10.");
        }
        
        return array(
            'results' => array(
                'byTitle' => $this->getSearchByTitle($query, $limit),
                'byMetadata' => $this->getSearchByMetadata($query, $limit)
            )
        );
    }
    
    /**
     * Search entire "search" index by title
     * 
     * @param string $query
     * @param integer $limit
     * @return array
     */
    public function getSearchByTitle($query, $limit)
    {
        $finder = $this->container->get('fos_elastica.index.search');

        $queryString = new ElasticaQueryString($query);
        $queryString->setFields(array('title'));
        
        $elasticaQuery = new ElasticaQuery;
        $elasticaQuery->setQuery($queryString);
        $elasticaQuery->setFields(array('title'));
        $elasticaQuery->setLimit($limit);
        
        $results = array();
        foreach ($finder->search($elasticaQuery) as $result) {
            $results[] = array(
                'type' => $result->getType(),
                'fields' => $result->getFields(),
            );
        }
        
        return $results;
    }
    
    /**
     * Search entire "search" index by metadata
     * 
     * @param string $query
     * @param integer $limit
     * @return array
     */
    public function getSearchByMetadata($query, $limit)
    {
        $finder = $this->container->get('fos_elastica.index.search');

        $queryString = new ElasticaQueryString($query);
        $queryString->setFields(array('description', 'overview', 'user.*'));
//            'createdAt', 'updatedAt', 
        
        $elasticaQuery = new ElasticaQuery;
        $elasticaQuery->setQuery($queryString);
        $elasticaQuery->setFields(array('title', 'description', 'overview'));
        $elasticaQuery->setLimit($limit);
        
        $results = array();
        foreach ($finder->search($elasticaQuery) as $result) {
            $results[] = array(
                'type' => $result->getType(),
                'fields' => $result->getFields(),
            );
        }
        
        return $results;
    }
    
}
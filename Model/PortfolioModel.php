<?php

namespace Websolutio\BlogBundle\Model;

use Doctrine\ORM\EntityManager;

/**
 * Portfolio Model.
 *
 */
class PortfolioModel
{
	// set up EntityManager constructor and define serice in /Resources/config to load Model in Controller with service container
	
	/**
     * @Var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
      $this->em = $em;
    }
	
	// get random record by category
    public function getRandomRecord($portcategory = null) {	

        $count = $this->em->createQuery("SELECT COUNT (p) FROM WebsolutioBlogBundle:Portfolio p WHERE  p.portcategory = ?1 AND p.publish = 1 ORDER BY p.created_at DESC "); 
        $count->setParameter(1, $portcategory);
        $ofcount = $count->getSingleScalarResult();
        $offset = rand(0, $ofcount - 1);
        $query = $this->em->createQuery("SELECT p FROM WebsolutioBlogBundle:Portfolio p WHERE  p.portcategory = ?1 AND p.publish = 1 ORDER BY p.created_at DESC ");  
        $query->setParameter(1, $portcategory);   
        $query->setFirstResult($offset);
        $query->setMaxResults(1);
        $entities = $query->getResult();
        
        return $entities;
    }

}

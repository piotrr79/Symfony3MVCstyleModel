<?php

namespace Websolutio\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Websolutio\BlogBundle\Entity\Portfolio;

/**
 * Portfolio controller.
 *
 */
class PortfolioController extends Controller
{    
    /**
     * Lists random Portfolio entities by category with business login in MVC style Model
     *
     */
    public function partialRandomAction($portcategory)
    {
		// load Portfolio Model as service
		$modelQuery = $this->container->get('websolutio_blog.portfolio_model');
		$portfolios = $modelQuery->getRandomRecord($portcategory);
		

        return $this->render('portfolio/partial.html.twig', array(
            'portfolios' => $portfolios,
        ));
    }

}

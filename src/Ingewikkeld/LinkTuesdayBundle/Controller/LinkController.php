<?php

namespace Ingewikkeld\LinkTuesdayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LinkController extends Controller
{
    /**
     * @Route("/", name="lt_homepage")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        return array(
            'recent'    => $em->getRepository('IngewikkeldLinkTuesdayBundle:Link')->getMostRecentLinks(),
            'top'       => $em->getRepository('IngewikkeldLinkTuesdayBundle:Link')->getMostPopularLinks(),
            'weektop'   => $em->getRepository('IngewikkeldLinkTuesdayBundle:Link')->getMostPopularLinksSince(date('d-m-Y', time()-(86400*7))),
        );
    }

    /**
     * @Route("/search", name="lt_search")
     * @Template()
     */
    public function searchAction()
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        return array(
            'results' => $em->getRepository('IngewikkeldLinkTuesdayBundle:Tweet')->search($this->getRequest()->get('term')),
            'term' => $this->getRequest()->get('term')
        );
    }

    /**
     * @Route("/feed", name="lt_feed")
     * @Method({"GET"})
     * @Template()
     */
    public function rssAction()
    {
      $em = $this->get('doctrine.orm.default_entity_manager');

      $items = $em->getRepository('IngewikkeldLinkTuesdayBundle:Link')->getMostRecentLinks();

      $templateItems = array();
      foreach($items as $item)
      {
        $templateItems[] = $item[0];
      }

      return array('items' => $templateItems, 'date' => date('c'));
    }
}

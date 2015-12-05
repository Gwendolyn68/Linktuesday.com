<?php

namespace Ingewikkeld\LinkTuesdayBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TweetRepository extends EntityRepository
{
    public function search($term) {
        return $this->_em->createQuery("SELECT t FROM IngewikkeldLinkTuesdayBundle:Tweet t WHERE t.content LIKE :term")->setParameter('term', '%' . $term . '%')->getResult();
    }
}

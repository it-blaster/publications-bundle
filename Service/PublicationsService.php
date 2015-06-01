<?php

namespace Fenrizbes\PublicationsBundle\Service;

use Fenrizbes\PublicationsBundle\Model\Publication;
use Fenrizbes\PublicationsBundle\Model\PublicationQuery;

class PublicationsService
{
    /**
     * Returns a piece of news by its slug
     *
     * @param $slug
     * @param null $type_key
     * @param null $criteria
     * @return Publication
     */
    public function findOne($slug, $type_key = null, $criteria = null)
    {
        return PublicationQuery::create(null, $criteria)
            ->applyBaseFilter($type_key)
            ->filterBySlug($slug)
            ->findOne()
        ;
    }

    /**
     * Returns a propel's pager
     *
     * @param string|null $type_key
     * @param int $limit
     * @param int $page
     * @param string $sort_order
     * @param null $criteria
     * @return \PropelModelPager
     */
    public function find($type_key = null, $limit = 10, $page = 1, $sort_order = \Criteria::DESC, $criteria = null)
    {
        return PublicationQuery::create(null, $criteria)
            ->applyBaseFilter($type_key)
            ->applySorting($sort_order)
            ->paginate($page, $limit)
        ;
    }

    /**
     * Returns a random set of publications
     *
     * @param string|null $type_key
     * @param int $limit
     * @param null $criteria
     * @return \PropelObjectCollection|Publication|null
     */
    public function getRandom($type_key = null, $limit = 1, $criteria = null)
    {
        return PublicationQuery::create(null, $criteria)
            ->applyBaseFilter($type_key)
            ->sortRandomly()
            ->findWithLimit($limit)
        ;
    }

    /**
     * Returns next publications
     *
     * @param Publication $current
     * @param int $limit
     * @param null $criteria
     * @return \PropelObjectCollection|Publication|null
     */
    public function getNext(Publication $current, $limit = 1, $criteria = null)
    {
        return $this->getSiblings($current, $limit, \Criteria::GREATER_THAN, $criteria);
    }

    /**
     * Returns previous publications
     *
     * @param Publication $current
     * @param int $limit
     * @param null $criteria
     * @return \PropelObjectCollection|Publication|null
     */
    public function getPrevious(Publication $current, $limit = 1, $criteria = null)
    {
        return $this->getSiblings($current, $limit, \Criteria::LESS_THAN, $criteria);
    }

    /**
     * Returns next or previous publications
     *
     * @param Publication $current
     * @param int $limit
     * @param string $comparison
     * @param null $criteria
     * @return \PropelObjectCollection|Publication|null
     */
    protected function getSiblings(Publication $current, $limit, $comparison, $criteria = null)
    {
        return PublicationQuery::create(null, $criteria)
            ->applyBaseFilter($current->getPublicationTypeKey())
            ->filterByCreatedAt($current->getCreatedAt(), $comparison)
            ->applySorting($comparison == \Criteria::GREATER_THAN ? \Criteria::ASC : \Criteria::DESC)
            ->findWithLimit($limit)
        ;
    }
}
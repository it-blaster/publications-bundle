<?php

namespace Fenrizbes\PublicationsBundle\Service;

use Fenrizbes\PublicationsBundle\Model\Publication;
use Fenrizbes\PublicationsBundle\Model\PublicationQuery;

class PublicationsService
{
    /**
     * Returns a publication by it's ID
     *
     * @param int $id
     * @return Publication|null
     */
    public function findOne($id)
    {
        return PublicationQuery::create()
            ->applyBaseFilter()
            ->findPk((int)$id)
        ;
    }

    /**
     * Returns a propel's pager
     *
     * @param string|null $type_key
     * @param int $limit
     * @param int $page
     * @param array $filters
     * @param string $sort_order
     * @return \PropelModelPager
     */
    public function find($type_key = null, $limit = 10, $page = 1, $filters = array(), $sort_order = \Criteria::DESC)
    {
        return PublicationQuery::create()
            ->applyBaseFilter($type_key)
            ->applyFilters($filters)
            ->applySorting($sort_order)
            ->paginate($page, $limit)
        ;
    }

    /**
     * Returns a random set of publications
     *
     * @param string|null $type_key
     * @param int $limit
     * @return \PropelObjectCollection|Publication|null
     */
    public function getRandom($type_key = null, $limit = 1)
    {
        return PublicationQuery::create()
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
     * @return \PropelObjectCollection|Publication|null
     */
    public function getNext(Publication $current, $limit = 1)
    {
        return $this->getSiblings($current, $limit, \Criteria::GREATER_THAN);
    }

    /**
     * Returns previous publications
     *
     * @param Publication $current
     * @param int $limit
     * @return \PropelObjectCollection|Publication|null
     */
    public function getPrevious(Publication $current, $limit = 1)
    {
        return $this->getSiblings($current, $limit, \Criteria::LESS_THAN);
    }

    /**
     * Returns next or previous publications
     *
     * @param Publication $current
     * @param int $limit
     * @param string $comparison
     * @return \PropelObjectCollection|Publication|null
     */
    protected function getSiblings(Publication $current, $limit, $comparison)
    {
        return PublicationQuery::create()
            ->applyBaseFilter($current->getPublicationTypeKey())
            ->filterByCreatedAt($current->getCreatedAt(), $comparison)
            ->applySorting($comparison == \Criteria::GREATER_THAN ? \Criteria::ASC : \Criteria::DESC)
            ->findWithLimit($limit)
        ;
    }
}
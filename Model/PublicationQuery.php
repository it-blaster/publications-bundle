<?php

namespace Fenrizbes\PublicationsBundle\Model;

use Fenrizbes\PublicationsBundle\Model\om\BasePublicationQuery;
use Symfony\Component\DependencyInjection\Container;

class PublicationQuery extends BasePublicationQuery
{
    /**
     * Applies base filters
     *
     * @param string|null $type_key
     * @return PublicationQuery
     */
    public function applyBaseFilter($type_key = null)
    {
        return $this
            ->filterByIsPublished(true)
            ->filterByCreatedAt(new \DateTime(), \Criteria::LESS_EQUAL)

            ->_if(!is_null($type_key))
                ->filterByPublicationTypeKey($type_key)
            ->_endif()
        ;
    }

    /**
     * Applies the sorting by date
     *
     * @param string|null $sort_order
     * @return PublicationQuery
     */
    public function applySorting($sort_order = null)
    {
        return $this
            ->_if(!is_null($sort_order))
                ->orderByCreatedAt($sort_order)
            ->_endif()
        ;
    }

    /**
     * Applies the random sorting
     *
     * @return PublicationQuery
     */
    public function sortRandomly()
    {
        return $this->addAscendingOrderByColumn('rand()');
    }

    /**
     * Applies the limitation and returns a publication or a collection of publications
     *
     * @param $limit
     * @return array|mixed|\PropelObjectCollection|Publication
     */
    public function findWithLimit($limit)
    {
        $this->limit($limit);

        if ($limit === 1) {
            return $this->findOne();
        }

        return $this->find();
    }

    /**
     * Applies the filter by year
     *
     * @param string|int $value
     * @return PublicationQuery
     * @throws \Exception
     */
    public function filterByYear($value)
    {
        if (is_null($value)) {
            return $this;
        }

        if (!preg_match('/^\d{4}$/', $value)) {
            throw new \Exception('Incorrect year value: '. $value);
        }

        return $this->filterByCreatedAt(array(
            'min' => $value .'-01-01 00:00:00',
            'max' => $value .'-12-31 23:59:59'
        ));
    }

    /**
     * Applies the filter by month
     *
     * @param string $value
     * @return PublicationQuery
     * @throws \Exception
     */
    public function filterByMonth($value)
    {
        if (is_null($value)) {
            return $this;
        }

        if (!preg_match('/^\d{4}\-\d{2}$/', $value)) {
            throw new \Exception('Incorrect month value: '. $value);
        }

        $datetime = new \DateTime($value .'-01 00:00:00');

        return $this->filterByCreatedAt(array(
            'min' => $datetime->format('Y-m-d H:i:s'),
            'max' => $datetime->format('Y-m-t 23:59:59')
        ));
    }

    /**
     * Applies the filter by day
     *
     * @param string $value
     * @return PublicationQuery
     * @throws \Exception
     */
    public function filterByDay($value)
    {
        if (is_null($value)) {
            return $this;
        }

        if (!preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $value)) {
            throw new \Exception('Incorrect day value: '. $value);
        }

        return $this->filterByCreatedAt(array(
            'min' => $value .' 00:00:00',
            'max' => $value .' 23:59:59'
        ));
    }
}

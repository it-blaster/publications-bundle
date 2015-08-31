<?php

namespace Fenrizbes\PublicationsBundle\Model;

use Fenrizbes\PublicationsBundle\Model\om\BasePublication;

class Publication extends BasePublication
{
  
    /**
     * Проверяем видимость новости для посетителей
     * @return bool
     * @throws \PropelException
     */
    public function getIsVisible()
    {
        if ($this->getIsPublished() && $this->getCreatedAt() <= new \DateTime('now')) {
            return true;
        }

        return false;
    }  
  
}

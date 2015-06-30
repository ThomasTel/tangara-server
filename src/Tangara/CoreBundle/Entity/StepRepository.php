<?php

namespace Tangara\CoreBundle\Entity;

use \Doctrine\ORM\EntityRepository;

/**
 * StepRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StepRepository extends EntityRepository
{
    
    public function getStepsNotIncluded(Course $course) {
        $query = $this->createQueryBuilder('s')
                ->leftJoin('s.courses', 'cs')
                ->where('cs.course IS NULL')
                ->orWhere('cs.course != :course')
                ->setParameter('course', $course);
        return $query->getQuery()->getResult();
    }
}
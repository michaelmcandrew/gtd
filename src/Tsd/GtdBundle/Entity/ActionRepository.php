<?php
namespace Tsd\GtdBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Tsd\GtdBundle\Entity\Project;

class ActionRepository extends EntityRepository
{
    public function findIncompleteActions(Project $project = null){
        $where = array('completed' => null);
        if($project){
            $where['project'] = $project->getId();
        }
        return $this->getEntityManager()->getRepository('TsdGtdBundle:Action')->findBy($where);
    }
}

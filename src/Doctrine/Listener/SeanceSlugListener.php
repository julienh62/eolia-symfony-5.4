<?php

namespace App\Doctrine\Listener;

use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Seance;

class SeanceSlugListener
{
    protected $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function prePersist(Seance $entity, LifecycleEventArgs $event)
    {
       

        if(empty($entity->getSlug())) {
            //sluggerInterface
           $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
      // dd("ça marche");
    }
}
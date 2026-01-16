<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PublicIdSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if (!property_exists($entity, 'publicId')) {
            return;
        }

        if (method_exists($entity, 'getPublicId') && $entity->getPublicId() !== null) {
            return;
        }

        $repository = $entityManager->getRepository($entity::class);

        do {
            $uniqueInt = rand(100, 1000000);
            $exist = $repository->findOneBy(['publicId' => $uniqueInt]);
        } while ($exist);

        if (method_exists($entity, 'setPublicId')) {
            $entity->setPublicId($uniqueInt);
        } else {
            $entity->publicId = $uniqueInt;
        }
    }
}

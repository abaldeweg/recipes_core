<?php

namespace App\EventSubscriber;

use App\Entity\Menu;
use App\Service\Plan\Plan;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class MenuSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em, private Plan $plan, private ContainerBagInterface $params)
    {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->process($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->process($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->process($args);
    }

    protected function process(LifecycleEventArgs $args): void
    {
        $menu = $args->getObject();

        if (!$menu instanceof Menu) {
            return;
        }

        $this->render($menu);
    }

    protected function render(Menu $menu): void
    {
        $dest = __DIR__.'/../../public/export/';
        $filename = $menu->getPlan()->getYear().'-'.$menu->getPlan()->getWeek();
        $data = $this->em->getRepository(Menu::class)->findBy(
            ['plan' => $menu->getPlan()->getId()],
            [
                'day' => 'ASC',
                'course' => 'ASC',
            ]
        );

        $startDate = new \DateTime();
        $startDate->setISODate(
            $menu->getPlan()->getYear(),
            $menu->getPlan()->getWeek()
        );

        $this->plan->create(
            $dest,
            $filename,
            [
                'logo' => $this->params->get('pdf_logo'),
                'footer' => $this->params->get('pdf_footer'),
                'week' => $menu->getPlan()->getWeek(),
                'startDate' => $startDate->format('d.m.Y'),
                'endDate' => $startDate->modify('+4 days')->format('d.m.Y'),
                'data' => $data,
            ]
        );
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }
}

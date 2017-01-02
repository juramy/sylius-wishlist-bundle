<?php
namespace Webburza\Sylius\WishlistBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Event\ResourceEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Webburza\Sylius\WishlistBundle\Model\WishlistInterface;
use Webburza\Sylius\WishlistBundle\Model\WishlistRepositoryInterface;

class WishlistListener
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * WishlistListener constructor.
     *
     * @param TranslatorInterface $translator
     * @param ContainerInterface $container
     */
    public function __construct(TranslatorInterface $translator, ContainerInterface $container)
    {
        $this->translator = $translator;
        $this->container = $container;
    }

    /**
     * Hook into the Wishlist's pre-remove event,
     * prevent removal if this is the only wishlist for the customer.
     *
     * @param ResourceControllerEvent $event
     */
    public function preRemove(ResourceControllerEvent $event)
    {
        /** @var WishlistInterface $wishlist */
        $wishlist = $event->getSubject();

        /** @var WishlistRepositoryInterface $wishlistRepository */
        $wishlistRepository = $this->container->get('webburza.repository.wishlist');

        // Get the number of wishlists for the customer
        $wishlistCount = $wishlistRepository->getCountForCustomer($wishlist->getCustomer());

        // If this is the only wishlist for the customer, stop the event
        if ($wishlistCount == 1) {
            $event->stop(
                'webburza.sylius.wishlist.cannot_delete',
                ResourceControllerEvent::TYPE_ERROR, [], 400
            );
        }
    }
}

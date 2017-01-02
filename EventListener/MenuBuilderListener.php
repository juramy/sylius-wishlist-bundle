<?php
namespace Webburza\Sylius\WishlistBundle\EventListener;

use Sylius\Bundle\WebBundle\Event\MenuBuilderEvent;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilderListener
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addBackendMenuItems(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();

        $menu['customer']
            ->addChild('webburza_sylius_wishlist', array(
                'route'           => 'webburza_wishlist_index',
                'labelAttributes' => array('icon' => 'glyphicon glyphicon-star'),
            ))
            ->setLabel($this->translator->trans('webburza.sylius.wishlist.backend.wishlists'));
    }
}

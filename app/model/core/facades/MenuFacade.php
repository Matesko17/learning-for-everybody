<?php

namespace App\Model\Facades;

use Doctrine\ORM\EntityManager;
use App\Model\Entities\MenuItem;
use App\Model\Repositories\MenuItemRepository;

/**
 * Class MenuFacade
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
class MenuFacade
{
    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->menuItemRepository = $this->entityManager->getRepository(MenuItem::class);
    }

    public function getMenuByIdent($ident, $lang)
    {
        $menu = $this->menuItemRepository->findBy(['menu.ident' => $ident, 'menu.locale' => $lang, 'show' => true], ['order']);
        return ($this->buildTree($menu, 0));
    }

    private function buildTree($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element->getParent() ? ($element->getParent()->getId() == $parentId) : $parentId == null) {
                $child = $this->buildTree($elements, $element->getId());
                if ($child) {
                    $element->hasChild = TRUE;
                    $element->child = $child;
                }else{
                    $element->hasChild = FALSE;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}

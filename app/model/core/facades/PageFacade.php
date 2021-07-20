<?php

namespace App\Model\Facades;

use App\Model\Entities\Page;
use App\Model\Repositories\PageRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class PageFacade
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
class PageFacade
{
    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pageRepository = $this->entityManager->getRepository(Page::class);
    }

    public function getPageByIdent($ident)
    {
        return $this->pageRepository->findOneBy(['ident' => $ident]);
    }

    public function getPageById($id)
    {
        return $this->pageRepository->findOneBy(['id' => $id]);
    }
}
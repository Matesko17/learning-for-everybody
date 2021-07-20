<?php

namespace App\Model\Facades;

use App\Model\Entities\SiteSetting;
use App\Model\Repositories\SiteSettingRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class SiteSettingFacade
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
class SiteSettingFacade
{
    /**
     * @var SiteSettingRepository
     */
    private $siteSettingRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->siteSettingRepository = $this->entityManager->getRepository(SiteSetting::class);
    }

    public function getSiteSettings()
    {
        $qb = $this->siteSettingRepository->createQueryBuilder('p')
                ->select('p')
                ->getQuery();

        return $qb->getResult();
    }

}
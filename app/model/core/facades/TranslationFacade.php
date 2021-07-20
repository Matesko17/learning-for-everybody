<?php

namespace App\Model\Facades;

use App\Model\Entities\Translation;
use Doctrine\ORM\{EntityManager, EntityRepository, OptimisticLockException, ORMException};

/**
 * Class TranslationSettingFacade
 *
 * @author  Kamil Walig <kamil.walig@q2.cz>
 * @author  Jakub Markus <jakub.markus@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @package Qetteweb
 */
class TranslationFacade
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @var EntityRepository
     */
    private $translationRepository;
    
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->translationRepository = $this->entityManager->getRepository(Translation::class);
    }
    
    public function getTranslations()
    {
        return $this->translationRepository->findAll();
    }
    
    /**
     * @param string $namespace
     * @param string $section
     * @param string $key
     * @param string $lang
     * @return null|object
     */
    public function hasEntity(string $namespace, string $section, string $key, string $lang)
    {
        return $this->translationRepository->findOneBy(
            ['namespace' => $namespace, 'section' => $section, 'key' => $key, 'lang' => $lang]
        );
    }
    
    /**
     * @param array|Translation $data
     * @return bool|object|null
     */
    public function hasEntityFrom($data)
    {
        if(is_array($data)) {
            if(isset($data['namespace']) and isset($data['section']) and isset($data['key']) and isset($data['lang'])) {
                return $this->hasEntity($data['namespace'], $data['section'], $data['key'], $data['lang']);
            }
        }
        
        if(is_a($data, Translation::class)) {
            /** @var Translation $data */
            return $this->hasEntity($data->getNamespace(), $data->getSection(), $data->getKey(), $data->getLang());
        }
        
        return false;
    }
    
    /**
     * set entities to import
     */
    public function prepareEntitiesForImport()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->update(Translation::class, 't')
            ->set('t.updated', 0)
            ->getQuery()
            ->execute();
    }
    
    /**
     * @param array $entities
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function importEntities(array $entities)
    {
        foreach($entities as $entity) {
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
    }
    
    /**
     * @param array $entities
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateEntities(array $entities)
    {
        foreach($entities as $entity) {
            if(isset($entity)) {
                $this->entityManager->merge($entity);
            }
        }
        $this->entityManager->flush();
    }
    
    /**
     * remove non exists translated entities
     * @throws ORMException
     */
    public function cleanUpTranslation()
    {
        $entities = $this->translationRepository->findBy(
            ['updated' => 0]
        );
        
        if(count($entities) > 0) {
            foreach($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
    }
    
    /**
     * Creates entity from array with values.
     * @param array $data
     * @return Translation|null
     */
    public function createEntity(array $data): ?Translation
    {
        /** @var Translation $entity */
        if($entity = $this->hasEntityFrom($data)) {
            $entity->setTranslate($data["translate"]);
        } else {
            $entity = new Translation();
            foreach($data as $key => $value) {
                if($key == "id") {
                    continue;
                }
                
                $method = "set".ucfirst($key);
                if(method_exists($entity, $method)) {
                    $entity->{$method}($value);
                } else {
                    return null;
                }
            }
        }
        
        return $entity;
    }
}

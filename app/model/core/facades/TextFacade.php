<?php

namespace App\Model\Facades;

use App\Model\Entities\Text;
use App\Model\Repositories\TextRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Nette\Utils\Strings;

/**
* Class TextFacade
*
* @author  Radek Fryšták <radek.frystak@q2.cz>
* @author  Martin Skyba <martin.skyba@q2.cz>
* @package Qetteweb
*/
class TextFacade
{
   /**
    * @var TextRepository
    */
   private $textRepository;

   /**
    * @var EntityManager
    */
   private $entityManager;

   /** @var array */
   private $allowLang;

   public function __construct(array $allowLang, EntityManager $entityManager)
   {
       $this->entityManager = $entityManager;
       $this->textRepository = $this->entityManager->getRepository(Text::class);
       $this->allowLang = $allowLang;
   }

   public function getAllTexts()
   {
       return $this->textRepository->findAssoc([], 'ident');
   }

   public function getTextByIdent($ident)
   {
       return $this->textRepository->findOneBy(['ident' => $ident]);
   }

   public function getTextById($id)
   {
       return $this->textRepository->findOneBy(['id' => $id]);
   }
    
    /**
     * @param string $ident
     * @return Text
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createNew($ident)
   {
       $values = [
           'ident' => Strings::webalize($ident),
       ];

       $text = new Text();
       $text->setIdent($values['ident']);

       return $this->save($text, $ident);
   }
    
    /**
     * @param Text $text
     * @param string $ident
     * @return Text
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function save($text, $ident)
   {
       $this->entityManager->persist($text);
       foreach ($this->allowLang as $key => $value) {
            $text->setCurrentLocale($key);
            $text->setTitle('## ' . $key . ' - ' . $ident . ' - title ##');
            $text->setContent('## ' . $key . ' - ' . $ident . ' - content ##');
            $text->addTranslation($text->translate($key));
       }
       $text->mergeNewTranslations();
       $this->entityManager->flush();

       return $text;
   }
}

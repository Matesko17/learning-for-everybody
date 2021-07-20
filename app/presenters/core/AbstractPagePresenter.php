<?php

namespace App\Presenters;

use App\Model\Facades\PageFacade;
use Nette\Application\BadRequestException;

/**
 * Class PagePresenter
 *
 * @author  Radek Fryšták <radek.frystak@q2.cz>
 * @author  Martin Skyba <martin.skyba@q2.cz>
 * @author  Tomáš Surovčík <tomas.surovcik@q2.cz>
 * @author  Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
abstract class AbstractPagePresenter extends BasePresenter
{
    /** @var PageFacade @inject */
    public $pageFacade;
    
    /**
     * @param $id
     * @throws BadRequestException
     */
    public function renderDefault($id)
    {
        if(is_numeric($id)) {
            $page = $this->pageFacade->getPageById($id);
        } else {
            $page = $this->pageFacade->getPageByIdent($id);
        }
        
        if(!$page) {
            throw new BadRequestException($this->translator->translate('errors.page.pageNotFound.header'), 404);
        }
        
        $this->template->page = $page;
    }
}

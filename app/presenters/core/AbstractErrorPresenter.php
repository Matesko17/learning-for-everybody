<?php

namespace App\Presenters;

use Nette\Application\Request;
use Nette\Application\IResponse;
use Nette\Application\IPresenter;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\ForwardResponse;
use Nette\Application\Responses\CallbackResponse;
use Nette\SmartObject;
use Tracy\ILogger;

abstract class AbstractErrorPresenter implements IPresenter
{
    use SmartObject;

    /** @var ILogger */
    private $logger;


    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @param Request $request
     * @return IResponse
     */
    public function run(Request $request)
    {
        $e = $request->getParameter('exception');

        if ($e instanceof BadRequestException) {
            // $this->logger->log("HTTP code {$e->getCode()}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", 'access');
            return new ForwardResponse($request->setPresenterName('Error4xx'));
        }

        $this->logger->log($e, ILogger::EXCEPTION);
        return new CallbackResponse(function () {
            require __DIR__ . '/../templates/Error/500.phtml';
        });
    }
}

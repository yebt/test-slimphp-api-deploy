<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class ShutdownHandler
{
    private Request $request;

    private HttpErrorHandler $httpErrorHandler;

    private bool $displayErrorDetails;

    public function __construct(
        Request $request,
        HttpErrorHandler $httpErrorHandler,
        bool $displayErrorDetails
    ) {
        $this->request = $request;
        $this->httpErrorHandler = $httpErrorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke(): void
    {
        $error = error_get_last();
        if ($error !== null && $error !== []) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = sprintf('FATAL ERROR: %s. ', $errorMessage);
                        $message .= sprintf(' on line %d in file %s.', $errorLine, $errorFile);
                        break;

                    case E_USER_WARNING:
                        $message = 'WARNING: ' . $errorMessage;
                        break;

                    case E_USER_NOTICE:
                        $message = 'NOTICE: ' . $errorMessage;
                        break;

                    default:
                        $message = 'ERROR: ' . $errorMessage;
                        $message .= sprintf(' on line %d in file %s.', $errorLine, $errorFile);
                        break;
                }
            }

            $httpInternalServerErrorException = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->httpErrorHandler->__invoke(
                $this->request,
                $httpInternalServerErrorException,
                $this->displayErrorDetails,
                false,
                false,
            );

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}

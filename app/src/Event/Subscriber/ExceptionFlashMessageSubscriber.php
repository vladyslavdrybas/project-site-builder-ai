<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ExceptionFlashMessageSubscriber implements EventSubscriberInterface
{
    protected string $environment;
    protected FlashBagInterface $flash;

    public function __construct(
        protected readonly string $projectEnvironment,
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly RequestStack $requestStack
    ) {
        $this->flash = $this->requestStack->getSession()->getFlashBag();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 100],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (str_starts_with($event->getRequest()->server->get('REQUEST_URI'), '/api')) {
            return;
        }

        $exception = $event->getThrowable();
        $code = Response::HTTP_BAD_REQUEST;
        $message = $exception->getMessage();
        if ($exception instanceof AccessDeniedException) {
            $code = Response::HTTP_UNAUTHORIZED;
            $message = 'Access denied';
        } else if ($exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = '404 not found';
        } else if ($exception instanceof MethodNotAllowedException) {
            $code = Response::HTTP_METHOD_NOT_ALLOWED;
            $message = 'Method not allowed';
        }

        $this->flash->set('danger', $message);

    }
}

<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use DateTime;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ExceptionJsonSubscriber implements EventSubscriberInterface
{
    protected string $environment;

    public function __construct(
        protected readonly string $projectEnvironment,
        protected readonly ParameterBagInterface $parameterBag
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 100],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_route');
        if (
            'cp_variant_builder_process_ajax' !== $route
            && !str_starts_with($event->getRequest()->server->get('REQUEST_URI'), '/api')
        ) {
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

        $data = [
            'status' => $code,
            'host' => $event->getRequest()->server->get('HOST'),
            'requestUri' => $event->getRequest()->server->get('REQUEST_URI'),
            'queryString' => $event->getRequest()->server->get('QUERY_STRING'),
            'environment' => $this->projectEnvironment,
            'service' => $this->parameterBag->get('service_name'),
            'version' => $this->parameterBag->get('api_version'),
            'time' => (new DateTime())->format(DateTimeInterface::W3C),
            'message' => $message,
        ];

        if ($this->projectEnvironment !== 'prod') {
            $data['trace'] = $exception->getTrace();
        }

        $event->setResponse(new JsonResponse($data, $code));
        // or stop propagation (prevents the next exception listeners from being called)
        $event->stopPropagation();
    }
}

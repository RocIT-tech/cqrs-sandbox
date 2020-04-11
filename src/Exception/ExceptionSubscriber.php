<?php

declare(strict_types=1);

namespace App\Exception;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use function get_class;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private const EXCEPTION_MAPPING = [
        'default'                                     => ['An error occured', Response::HTTP_INTERNAL_SERVER_ERROR],
        ForeignKeyConstraintViolationException::class => ['Resource not found', Response::HTTP_NOT_FOUND],
        UniqueConstraintViolationException::class     => ['Duplicate', Response::HTTP_CONFLICT],
    ];

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => [
                ['convertToJson', 10],
            ],
        ];
    }

    public function convertToJson(ExceptionEvent $exceptionEvent): void
    {
        $request = $exceptionEvent->getRequest();

        if (
            false === $request->isXmlHttpRequest()
            && 'json' !== $request->getContentType()
        ) {
            return;
        }

        $exception = $exceptionEvent->getThrowable();

        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        if (true === ($exception instanceof HttpExceptionInterface)) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } elseif (true === ($exception instanceof ValidationFailedException)) {
            $response->setContent($this->serializer->serialize($exception->getViolations(), JsonEncoder::FORMAT));
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } else {
            [$title, $code] = self::EXCEPTION_MAPPING[get_class($exception)] ?? self::EXCEPTION_MAPPING['default'];

            $response->setContent($this->serializer->serialize([
                'type'   => 'https://symfony.com/errors/validation',
                'title'  => $title,
                'detail' => $exception->getMessage(),
            ], JsonEncoder::FORMAT));
            $response->setStatusCode($code);
        }

        $exceptionEvent->setResponse($response);
    }
}

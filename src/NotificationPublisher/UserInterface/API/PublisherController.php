<?php

declare(strict_types=1);

namespace App\NotificationPublisher\UserInterface\API;

use App\NotificationPublisher\Application\Command\SendMessageCommand;
use App\NotificationPublisher\UserInterface\Form\MessageForm;
use App\Shared\Request\JsonContentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class PublisherController extends AbstractController
{
    public function send(
        JsonContentRequest $request,
        MessageBusInterface $bus
    ): JsonResponse {
        $form = $this->createForm(MessageForm::class, new SendMessageCommand());
        $form->submit($request->getJsonContent(), false);

        if (!$form->isValid()) {
            return new JsonResponse($form->getErrors(true), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bus->dispatch($form->getData());

        return new JsonResponse(status: JsonResponse::HTTP_NO_CONTENT);
    }
}

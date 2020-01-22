<?php


namespace Morebec\Orkestra\Notification;

/**
 * The notification bus is responsible for dispatching the notifications
 * to the right notification handlers, that will apply their logic for sending
 * an actual notification.
 */
interface NotificationBusInterface
{
    /**
     * @param NotificationInterface $notification
     * @return void
     */
    public function dispatch(NotificationInterface $notification): void;
}
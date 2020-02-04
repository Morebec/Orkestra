<?php


namespace Morebec\Orkestra\Messaging\Notification;

/**
 * Interface NotificationSubscriberInterface
 * Similarly to an NotificationHandler, an Notification Subscriber is responsible for reacting to notifications dispatched through an notification bus.
 * Unlike an NotificationHandler, An NotificationSubscriber is used to listen to multiple notifications instead of a single one.
 * Notification Handling method should be constructed as follows:
 *  - Single type hinted argument of type NotificationInterface
 */
interface NotificationSubscriberInterface
{
}

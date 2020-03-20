<?php

namespace Morebec\Orkestra\Messaging\Notification;

/**
 * Responsible for receiving notifications and sending them to the user
 * using their own logic, Synchronously or asynchronously..
 * Examples of notifications can be SMS, Email, Push Notification, Web Notification streams etc.
 * Most of these handlers reside on the infrastructure layer as sending an email
 * is not the domain's job, it is technology specific.
 * However, not that some handlers can be part of the domain, such as the ones constructing notification streams
 * to be displayed on the front end.
 *
 * To implement this interface, create a method __invoke taking as a parameter the type
 * of notification it expects.
 *
 * @template T of NotificationInterface
 */
interface NotificationHandlerInterface
{
}

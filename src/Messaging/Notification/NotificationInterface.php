<?php


namespace Morebec\Orkestra\Messaging\Notification;

/**
 * Interface for all types of notifications that are meant to be sent/displayed to a user
 * such as email, SMS notifications, Push Notifications, or in app notifications.
 * They follow the same kind of system as a message bus (command bus, event bus, query bus etc.)
 * And are handled appropriately by a NotificationHandler.
 */
interface NotificationInterface
{
}

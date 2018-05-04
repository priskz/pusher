<?php

namespace Priskz\Pusher\Ratchet;

use Ratchet\ConnectionInterface;
use Priskz\Pusher\SubscriptionInterface;

abstract class AbstractSubscriptionPusher implements SubscriptionInterface
{
    /**
     * A lookup of all the channels clients have subscribed to
     */
    protected $subscribedChannels = [];

    public function onSubscribe(ConnectionInterface $conn, $channel)
    {
        $this->subscribedChannels[$channel->getId()] = $channel;

         echo "New Subscribe to: ({$channel})\n";
    }

    public function onUnSubscribe(ConnectionInterface $conn, $channel)
    {

    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn)
    {

    }

    public function onCall(ConnectionInterface $conn, $id, $channel, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $channel, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $channel, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {

    }

    /**
     * Push socket messages to subscribers of individual channels.
     * 
     * @param  $message JSON string.
     * @return void
     */
    abstract public function push($message);
}
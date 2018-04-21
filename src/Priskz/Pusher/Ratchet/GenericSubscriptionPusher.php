<?php namespace Priskz\Pusher\Ratchet;

use Priskz\Pusher\Ratchet\AbstractSubscriptionPusher;

class GenericSubscriptionPusher extends AbstractSubscriptionPusher
{
    /**
     * Push socket messages to subscribers of individual channels.
     * Broadcasting to 'all' subscribers is possible via the 'global' channel.
     * 
     * @param json
     * @return void
     */
    public function push($socketMessage)
    {
        $message = json_decode($socketMessage, true);

        if($message['channel'] === 'global')
        {
            // If no one is subscribed to listen stop here.
            if(! empty($this->subscribedChannels))
            {
                return;
            }

            // Send the socket message to everyone listening on every channel.
            foreach( $this->subscribedChannels as $channel )
            {
                $channel->broadcast($message);
            }

            // This was a global broadcast, so we can stop here.
            return;
        }

        // If non-global channel is nonexistent, do nothing else.
        if(!array_key_exists($message['channel'], $this->subscribedChannels))
        {
            return;
        }

        $channel = $this->subscribedChannels[$message['channel']];

        // re-send the data to all the clients subscribed to that category
        $channel->broadcast($message);
    }
}
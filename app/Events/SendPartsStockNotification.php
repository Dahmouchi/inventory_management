<?php

namespace App\Events;

use App\Http\Resources\PartsResourserc;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Parts;

class SendPartsStockNotification  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message ;
    public  $part ;


    /**
     * Create a new event instance.
     */
    public function __construct(Parts $part, $message )
    {
        $this->part = $part;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('parts-stock');
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'parts-stock-notification';
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'part' => new PartsResourserc($this->part),
        ];
    }

}

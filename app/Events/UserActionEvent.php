<?php
// app/Events/UserActionEvent.php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $action;
    public $data;
    public $relatedModel;

    public function __construct(User $user, string $action, array $data = [], $relatedModel = null)
    {
        $this->user = $user;
        $this->action = $action;
        $this->data = $data;
        $this->relatedModel = $relatedModel;
    }
}
<?php
 
namespace App\View\Composers;
 
use App\Repositories\UserRepository;
use Illuminate\View\View;
 
class NotificationComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct() 
    {}
 
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $notification = true;
        $view->with('notification', $notification);
    }
}
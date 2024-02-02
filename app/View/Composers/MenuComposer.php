<?php
 
namespace App\View\Composers;
 
use App\Repositories\UserRepository;
use Illuminate\View\View;
use App\Models\Menu;
 
class MenuComposer
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
        $qy = Menu::select('*');
        $menu = $qy->get();
        $view->with('menu',$menu);
    }
}
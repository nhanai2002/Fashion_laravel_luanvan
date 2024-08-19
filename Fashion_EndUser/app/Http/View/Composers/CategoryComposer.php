<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use FashionCore\Interfaces\ICategoryRepository;

class CategoryComposer
{
    public $categoryRepo;
    public function __construct(ICategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function compose(View $view)
    {
        $view->with('categories', $this->categoryRepo->getAll());
    }
}

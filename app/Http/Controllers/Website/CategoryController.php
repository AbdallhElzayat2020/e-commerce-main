<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Website\HomeService;

class CategoryController extends Controller
{
    protected $homeService;
    public function __construct(HomeService $home_service)
    {
        $this->homeService = $home_service;
    }

    public function getCategories()
    {
        $categories = $this->homeService->getCategories();
        return view('website.categories', compact('categories'));
    }

    public function getProductsByCategory($slug)
    {
        $products = $this->homeService->getProductsByCategory($slug);
        return view('website.products',compact('products'));
    }
}

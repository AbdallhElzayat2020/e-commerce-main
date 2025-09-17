<?php

namespace App\Livewire\Website;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // or 'tailwind'

    public $categories;
    public $brands;

    public $currentProductsCount=9,$totalProducts=9;

    public array $categoryIds = [];
    public array $brandIds = [];
    public $minPrice = 0;
    public $maxPrice = 10000;

    protected $queryString = [
        'categoryIds' => ['except' => []],
        'brandIds' => ['except' => []],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 10000],
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
    }

    public function updated($property)
    {
        // $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->brandIds = [];
        $this->categoryIds = [];
        $this->setPriceRange(0, 10000);
    }

    public function setPriceRange($min, $max)
    {
        $this->minPrice = (int)$min;
        $this->maxPrice = (int)$max;
        $this->resetPage();
    }

    public function getFilteredProductsProperty()
    {
        return Product::with(['brand', 'category', 'images'])
            ->when(!empty($this->categoryIds), fn($q) => $q->whereIn('category_id', $this->categoryIds))
            ->when(!empty($this->brandIds), fn($q) => $q->whereIn('brand_id', $this->brandIds))
            ->where(function ($q) {
                $q->where(function ($query) {
                    $query->where('has_discount', 0)
                        ->whereBetween('price', [$this->minPrice, $this->maxPrice]);
                })->orWhere(function ($query) {
                    $query->where('has_discount', 1)
                        ->whereRaw('(price - discount) BETWEEN ? AND ?', [$this->minPrice, $this->maxPrice]);
                })->orWhereRelation('variants', function ($query) {
                    $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);
                });
            })
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.website.shop', [
            'products' => $this->filteredProducts,
        ]);
    }
}

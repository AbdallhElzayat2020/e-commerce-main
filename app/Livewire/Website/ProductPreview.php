<?php

namespace App\Livewire\Website;

use Livewire\Component;

class ProductPreview extends Component
{
    public $product;
    public $review;

    public function mount($product)
    {
        $this->product = $product;
        $this->product->load('productPreviews');
    }

    public function submitReview()
    {
        // TODO: Implement review submission logic
        $this->validate([
            'review' => 'required|string|max:255',
        ]);
        // TODO: Save the review to the database
        $this->product->productPreviews()->create([
            'comment' => $this->review,
            'user_id' => auth('web')->user()->id,
        ]);

        $this->reset('review');
        $this->dispatch('reviewSubmitted',__('website.review_submitted'));

    }

    public function render()
    {
        return view('livewire.website.product-preview');
    }


}

<div>
    @if ($screen == 'reviews')
        <div class="tab-pane fade @if ($screen == 'reviews') active show @endif">
            <div class="top-selling-section">
                <div class="row g-5">
                    @if ($reviews->count() > 0)
                        @foreach ($reviews as $item)
                            <div class="col-md-6">
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <img src="{{ asset('uploads/products/' . $item->product->images->first()->file_name) }}"
                                            alt="product-img">
                                    </div>
                                    <div class="product-info">
                                        <div class="review-date">
                                            <p>{{ $item->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="ratings">
                                            <span>
                                                <svg width="75" height="15" viewBox="0 0 75 15" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M7.5 0L9.18386 5.18237H14.6329L10.2245 8.38525L11.9084 13.5676L7.5 10.3647L3.09161 13.5676L4.77547 8.38525L0.367076 5.18237H5.81614L7.5 0Z"
                                                        fill="#FFA800" />
                                                    <path
                                                        d="M22.5 0L24.1839 5.18237H29.6329L25.2245 8.38525L26.9084 13.5676L22.5 10.3647L18.0916 13.5676L19.7755 8.38525L15.3671 5.18237H20.8161L22.5 0Z"
                                                        fill="#FFA800" />
                                                    <path
                                                        d="M37.5 0L39.1839 5.18237H44.6329L40.2245 8.38525L41.9084 13.5676L37.5 10.3647L33.0916 13.5676L34.7755 8.38525L30.3671 5.18237H35.8161L37.5 0Z"
                                                        fill="#FFA800" />
                                                    <path
                                                        d="M52.5 0L54.1839 5.18237H59.6329L55.2245 8.38525L56.9084 13.5676L52.5 10.3647L48.0916 13.5676L49.7755 8.38525L45.3671 5.18237H50.8161L52.5 0Z"
                                                        fill="#FFA800" />
                                                    <path
                                                        d="M67.5 0L69.1839 5.18237H74.6329L70.2245 8.38525L71.9084 13.5676L67.5 10.3647L63.0916 13.5676L64.7755 8.38525L60.3671 5.18237H65.8161L67.5 0Z"
                                                        fill="#FFA800" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="product-description">
                                            <a href="{{ route('website.products.show', $item->product->slug) }}"
                                                class="product-details">
                                                {{ $item->product->name }}
                                            </a>
                                            <p>
                                                {{ $item->comment }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="product-cart-btn">
                                        <a href="" wire:click.prevent="editReview({{ $item->id }})"
                                            class="product-btn">Edit Review</a>
                                        <a href="" data-id={{ $item->id }} class="product-btn delete-review">
                                            Delete Review
                                        </a>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $reviews->links() }}
                    @else
                        <section class="blog about-blog footer-padding">
                            <div class="container">
                                <div class="blog-item" data-aos="fade-up">
                                    <div class="cart-img">
                                        <img src="{{ asset('assets/website/assets/images/homepage-one/empty-wishlist.webp') }}"
                                            alt>
                                    </div>
                                    <div class="cart-content">
                                        <p class="content-title">Empty! You don’t Have Previews In This Section</p>
                                        <a href="{{ route('website.shop') }}"
                                            class="shop-btn">{{ __('website.back_to_shop') }}</a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Review</h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" wire:model="editReviewComment" rows="4"></textarea>
                        @error('editReviewComment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('showModal', false)">Close</button>
                        <button class="btn btn-primary" wire:click="updateReview">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        $wire.on('reviewUpdated', (event) => {
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: event,
                showConfirmButton: false,
                timer: 3000,
            });
        });

        // delete review
        $(document).on('click', '.delete-review', function(e) {
            e.preventDefault();
            var review_id = $(this).attr('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('deleteReview', {
                        reviewId: review_id
                    });
                }
            });

        });

        $wire.on('reviewDeleted', (event) => {
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: event,
                showConfirmButton: false,
                timer: 3000,
            });
        });
    </script>
@endscript

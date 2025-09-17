  <div class="product-review-section">
      <h5 class="intro-heading">{{ __('website.reviews') }}</h5>
      <div class="review-wrapper">

          @auth('web')
              <label for="review" class="mb-2">{{ __('website.write_a_review') }}</label>
              <div class="d-flex mb-4">
                  <input wire:model="review" id="review" style="height: 40px" class="form-control me-2" type="text"
                      placeholder="{{ __('website.write_a_review') }}" style="flex: 1;"><br>
                  @error('review')
                      <strong class="text-danger">{{ $message }}</strong>
                  @enderror
                  <button wire:click="submitReview" type="button"
                      class="btn btn-primary">{{ __('website.submit') }}</button>
              </div>
          @endauth

          @forelse ($product->productPreviews as $item)
              <div class="wrapper mb-4 p-3 border rounded">
                  <div class="wrapper-aurthor d-flex justify-content-between align-items-center mb-2">
                      <div class="wrapper-info d-flex align-items-center">
                          <div class="aurthor-img me-3">
                              <img src="{{ asset('assets/website/assets/images/homepage-one/aurthor-img-1.webp') }}"
                                  alt="aurthor-img" class="rounded-circle" width="50" height="50">
                          </div>
                          <div class="author-details">
                              <h5 class="mb-1">{{ $item->user->name }}</h5>
                              <p class="mb-0 text-muted">{{ $item->user->country->name }},
                                  {{ $item->user->city->name }}</p>
                          </div>
                      </div>
                      <div class="ratings d-flex align-items-center">
                          <span class="me-1">
                              {{-- SVG stars --}}
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
                          <span>(5.0)</span>
                      </div>
                  </div>
                  <div class="wrapper-description">
                      <p class="wrapper-details mb-0">{{ $item->comment }}</p>
                  </div>
              </div>
          @empty
              <p class="text-muted">{{ __('website.no_reviews_yet') }}</p>
          @endforelse
      </div>
  </div>

  @script
      <script>
          $wire.on('reviewSubmitted', (event) => {
              Swal.fire({
                  position: "top-center",
                  icon: "success",
                  title: event,
                  showConfirmButton: false,
                  timer: 1500
              });
          });
      </script>
  @endscript

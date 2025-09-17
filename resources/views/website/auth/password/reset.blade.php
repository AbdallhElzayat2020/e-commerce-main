@extends('layouts.website.app')
@section('title', __('website.email'))
@section('content')
<section class="login footer-padding">
    <div class="container">
      <div class="login-section">
        <div class="review-form">
          <h5 class="comment-title">{{__('dashboard.email')}}</h5>
          @if($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
          <form id="formLogin" action="{{route('password.update')}}" method="POST">
            @csrf
            <div class="review-inner-form">
                <div class="review-form-name">

                    <input name="token" value="{{ $token }}" hidden>

                    <label for="email" class="form-label">{{__('dashboard.email')}}*</label>
                    <input
                      name="email"
                      type="email"
                      id="email"
                      class="form-control"
                      value="{{ $email }}"
                      placeholder="{{__('dashboard.email')}}"
                    />
                  </div>
                  <div class="review-form-name">
                    <label for="email" class="form-label">{{__('dashboard.password')}}*</label>
                    <input
                      name="password"
                      type="password"
                      id="email"
                      class="form-control"

                      placeholder="{{__('dashboard.email')}}"
                    />
                  </div>
                  <div class="review-form-name">
                    <label for="email" class="form-label">{{__('dashboard.password_confirmation')}}*</label>
                    <input
                      name="password_confirmation"
                      type="password"
                      id="email"
                      class="form-control"
                      placeholder="{{__('dashboard.password_confirmation')}}"
                    />
                  </div>

            </div>
            <div class="login-btn text-center">
              <a href="javascript:void(0)" onClick="document.getElementById('formLogin').submit()" class="shop-btn">{{__('dashboard.login')}}</a>
              <span class="shop-account"
                >{{__('dashboard.login')}} ?<a href="{{route('website.login.get')}}"
                  > {{__('dashboard.login')}}</a
                ></span
              >
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection

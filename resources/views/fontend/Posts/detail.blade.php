@extends('fontend.layouts.guest')
@section('content')
<section class="breadcrumb-section mb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('layout.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('cart') }}">{{ __('layout.news') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data->title }}</li>
                  </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb -->
 
    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>{{ __('layout.product') }}</h4>
                            <ul>
                                @foreach($category as $row)
                                <li><a href="{{ url('category',$row->slug) }}">{{ $row->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4>{{ __('layout.search') }}</h4>
                            <div class="sidebar__item__size">
                                <label for="large">
                                    Large
                                    <input type="radio" id="large">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="medium">
                                    Medium
                                    <input type="radio" id="medium">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="small">
                                    Small
                                    <input type="radio" id="small">
                                </label>
                            </div>
                            <div class="sidebar__item__size">
                                <label for="tiny">
                                    Tiny
                                    <input type="radio" id="tiny">
                                </label>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                  <div class="mb-4">
                    <h3>{{ $data->title }}</h3>
                    <p>title: {{ $data->title }}</p>
                    <p>description: {{ $data->description }}</p>
                    <p>keyword: {{ $data->keyword }}</p>
                    {!! $data->content !!}  
                  </div>

                  <div class="blog__sidebar__item">
                            <h4>BÀI VIẾT KHÁC</h4>
                            <div class="blog__sidebar__recent">
                                @foreach($post_same as $item)
                                <a href="{{ url('posts',$item->slug) }}" class="blog__sidebar__recent__item">
                                    <div class="blog__sidebar__recent__item__pic">                                        
                                        @if (!empty($item->photo))
                                        <img src="{{ asset('upload/post/'.$item->photo) }}" style="width: 100px;">
                                        @endif                                        
                                    </div>
                                    <div class="blog__sidebar__recent__item__text">
                                        <h6>{{ $item->title }}</h6>
                                        <span>MAR 05, 2019</span>
                                    </div>
                                </a>
                                @endforeach
 
                            </div>
                        </div> 
                </div>
              </div>
        </div> 
    </section>
@endsection
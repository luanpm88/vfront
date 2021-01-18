@extends('fontend.layouts.guest')
@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('layout.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('posts') }}">{{ __('layout.news') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('layout.news_list') }}</li>
                      </ol>
                    </nav>                     
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

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
                    
                    <div class="row"> 
                         
					@foreach($data as $row)
						<div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic">
                                    <a href="{{ url('posts',$row->slug) }}" class="wrap-pic-w hov1 trans-03">
									@if (!empty($row->photo))
				                    <img src="{{ asset('upload/post/'.$row->photo) }}">
				                    @endif
									</a>
                                </div>
                                <div class="blog__item__text">
                                    <ul>
                                        <li><i class="fa fa-calendar-o"></i> May 4,2019</li>
                                        <li><i class="fa fa-comment-o"></i> 5</li>
                                    </ul>
                                    <h5><a href="{{ url('posts',$row->slug) }}">{{ $row->title }}</a></h5>
                                    <p>{{ $row->description }}</p> 
                                </div>
                            </div>
                        </div>

		                @endforeach
                    </div>
                    <div>
                        {{ $data->links() }}
                         
                    </div>
                    

                </div>
            </div>
        </div>
    </section>
    
@endsection()

 
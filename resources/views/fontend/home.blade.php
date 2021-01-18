@extends('fontend.layouts.master')
@section('content')

<!-- Featured Section Begin -->
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>{{ __('layout.product_new') }}</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">{{ __('layout.all') }}</li>
                            @foreach($category_hot as $key => $value)
                            <li data-filter=".cat{{ $key }}">{{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter">
                @foreach($products_hot as $row)
                <div class="col-lg-3 col-md-4 col-sm-6 mix cat{{ $row->category_id }}">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="{{ asset('upload/Product/'.$row->photo) }}">
                            <ul class="featured__item__pic__hover">
                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="{{ url('add-to-cart',$row->id) }}"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__discount__item__text">
                            <span>Dried Fruit</span>
                            <h5><a href="{{ url('product',$row->id) }}">{{ $row->title }}</a></h5> 
                            <div class="product__item__price">
                                {{ number_format(  $row->price * ( 100- $row->saleoff )/100 ,0) }} đ <span>{{ number_format($row->price,0) }} đ</span>
                            </div>
                        </div>
                    </div> 
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Featured Section End -->  
@endsection()
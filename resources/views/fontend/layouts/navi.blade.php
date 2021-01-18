<!-- Hero Section Begin -->
<section class="hero">
    <div class="container">
        <div class="row"> 
            <div class="col-lg-12">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="{{ url('/search') }}" method="post">
                            @csrf
                            <div class="hero__search__categories">
                                {{ __('layout.search') }}
                            </div>
                            <input type="text" name="txtkeyword" placeholder="{{ __('layout.keyword') }}">
                            <button type="submit" class="site-btn">{{ __('layout.search') }}</button>
                        </form>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <a href="tel:0906 668 703"><i class="fa fa-phone"></i></a>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5><a href="tel:1900 56 1268">1900 56 1268</a></h5>
                            <span>{{ __('layout.online') }} 24/7</span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->
  <section class="hero">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>CHỦNG LOẠI</span>
                        </div>
                        <ul>
                            @foreach($maincate as $cate)
                            <li><a href="{{ url('category',$cate->slug) }}">{{ $cate->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__item set-bg" data-setbg="img/hero/banner.jpg">
                        <div class="hero__text">
                            <span>HK FASHION</span>
                            <h2>HÀNG HIỆU <br />100% coton</h2>
                            <p>Khuyến mại cực lớn, thỏa sức mua sắm </p>
                            <a href="#" class="primary-btn">> XEM KHUYẾN MÃI <</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
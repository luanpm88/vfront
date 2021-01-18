<!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                        </div>
                        <ul>
                            <li>Địa chỉ: 60-49 Road 11378 New York</li>
                            <li>Điện thoại: 1900 56 1268</li>
                            <li>Email: info@ecoworld.vn</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>{{ __('layout.support') }}</h6>
                        <ul>
                            <li><a href="{{ url('/product') }}">{{ __('layout.product') }}</a></li>
                            <li><a href="{{ url('/contact') }}">{{ __('layout.feedback') }}</a></li>
                            <li><a href="{{ url('/agency') }}">{{ __('layout.agency') }}</a></li> 
                        </ul> 
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>{{ __('layout.receive_email') }}</h6>
                        <p>{{ __('layout.input_alias') }}</p>
                        <form action="#">
                            <input type="text" placeholder="{{ __('layout.input_email') }}">
                            <button type="submit" class="site-btn">{{ __('layout.signup') }}</button>
                        </form>
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text">
                        	<p>
                        		{{ __('layout.copyright') }} Eco World
							</p>
						</div>
                        <div class="footer__copyright__payment"><img src="img/payment-item.png" alt=""></div>

                    </div>

                </div>

            </div>

        </div>

    </footer>

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('js/mixitup.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);
</script>
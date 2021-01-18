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
                        <li class="breadcrumb-item"><a href="{{ url('products') }}">{{ __('layout.product') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $data->title }}</li>
                      </ol>
                    </nav>                     
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="{{ asset('upload/Product/'.$data->photo) }}" alt="">
                        </div>
                        <!--div class="product__details__pic__slider owl-carousel">
                            <img data-imgbigurl="{{ asset('ogani/img/product/details/product-details-2.jpg') }}"
                                src="{{ asset('ogani/img/product/details/thumb-1.jpg') }}" alt="">
                            <img data-imgbigurl="{{ asset('ogani/img/product/details/product-details-3.jpg') }}"
                                src="{{ asset('ogani/img/product/details/thumb-2.jpg') }}" alt="">
                        </div-->
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ $data->title }}</h3>
                        <div class="product__details__rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(18 reviews)</span>
                        </div>
                        <div class="product__details__price">{{ number_format($data->price,0) }} đ</div>
                        <p>{!! $data->description !!}</p>
                        <form method="post" action="{{ url('add-to-cart',$data->id) }}">
                            @csrf
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty">
                                      <input type="text" name="quantity" value="1">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="primary-btn" data-id="{{ $data->id }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ __('layout.cart_add') }}</button> 
                        </form>                        

                        <ul>
                            <li><b>{{ __('layout.status') }}</b> 
                                @if($data->notestatus)
                                    <span>{{ $data->notestatus }}</span>
                                @else
                                    <span>{{ $data->status }}</span>
                                @endif
                            </li>
                            <li><b>{{ __('layout.shipping') }}</b> <span>{{ $data->shipping }}</span></li>
                            <li><b>{{ __('layout.weight') }}</b> <span>{{ $data->weight }}</span></li>
                            <li><b>{{ __('layout.share') }}</b>
                                <div class="share">
                                    <a href="{{ url('product/'.$data->slug) }}"><i class="fa fa-facebook"></i></a>
                                    <a href="{{ url('product/'.$data->slug) }}"><i class="fa fa-twitter"></i></a>
                                    <a href="{{ url('product/'.$data->slug) }}"><i class="fa fa-instagram"></i></a>
                                    <a href="{{ url('product/'.$data->slug) }}"><i class="fa fa-pinterest"></i></a>
                                </div>

                            </li>

                        </ul>

                    </div>

                </div>

                <div class="col-lg-12">

                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">{{ __('layout.product_details') }}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab"
                                    aria-selected="false">{{ __('layout.product_feedback') }}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                    aria-selected="false">Comments</a>
                            </li> 
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>{{ __('layout.product_details') }}</h6> 
                                    {!! $data->content !!}
                                </div>
                            </div>

                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>{{ __('layout.product_feedback') }}</h6>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Comment</h6>






<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                <p class="text-secondary text-center">15 Minutes Ago</p>
            </div>
            <div class="col-md-10">
                <p>
                    <a class="float-left" href="https://maniruzzaman-akash.blogspot.com/p/contact.html"><strong>Maniruzzaman Akash</strong></a>
                    <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                    <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                    <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                    <span class="float-right"><i class="text-warning fa fa-star"></i></span>

               </p>
               <div class="clearfix"></div>
                <p>Lorem Ipsum is simply dummy text of the pr make  but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <p>
                    <a class="float-right btn text-white btn-danger ml-2 "> <i class="fa fa-heart"></i> Like</a> 
                    <a class="float-right btn btn-outline-primary reply" data-id="{{ $data->id }}" > <i class="fa fa-reply"></i> Reply</a>
               </p>
            </div>
        </div>
            
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                <p class="text-secondary text-center">15 Minutes Ago</p>
            </div>
            <div class="col-md-10">
                <p><a href="https://maniruzzaman-akash.blogspot.com/p/contact.html"><strong>Maniruzzaman Akash</strong></a></p>
                <p>Lorem Ipsum is simply dummy text of the pr make  but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <p>
                    <a class="float-right btn text-white btn-danger ml-2"> <i class="fa fa-heart"></i> Like</a> 
                    <a class="float-right btn btn-outline-primary reply" data-id="{{ $data->id }}" >  <i class="fa fa-reply"></i> Reply</a>
               </p>
            </div>
        </div>
    </div>
</div>









                                </div>
                            </div>
                             
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Product Details Section End -->
 

					
 
 

@endsection



@section('scripts')



	<script type="text/javascript">

        $(document).ready(function(){
            $('a.reply').click(function() {
                $(this).after('<div class="messages"></div><div class="controls"><div class="row"><div class="col-md-12"><div class="form-group"><label for="form_message">Bình luận của bạn *</label><textarea id="form_message" name="message" class="form-control" placeholder="Nội dung bình luận của bạn" rows="4" required="required" data-error="Please, leave us a message."></textarea><div class="help-block with-errors"></div></div></div><div class="col-md-12"><input type="submit" class="btnreply btn btn-success btn-send" value="Gửi Bình luận"></div></div><div class="row"><div class="col-md-12"><p class="text-muted">Bình luận của bạn sẽ được kiểm duyệt theo đúng chính sách hoạt động website.</p></div></div></div>');
                return false;
            });     
        });

        $(".btnreply").click(function (e) {    
            e.preventDefault();
               var ele = $(this);
               $.ajax({
                   url: '{{ url('product/comment/addnew') }}',
                   method: "patch",
                   data: {_token: '{{ csrf_token() }}'}, //, id: ele.attr("data-id"), quantity: ele.parents("p").find(".quantity").val()
                   success: function (response) {
                       window.location.reload();
                   }
                });
            });

        $(".addcart").click(function (e) {
           e.preventDefault();
           var ele = $(this); 
            $.ajax({
               url: '{{ url('add-to-cart') }}',
               method: "patch",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("p").find(".quantity").val()},
               success: function (response) {
                   window.location.reload();
               }
            });
        });
	</script>

    @foreach($category as $row)
    <a href="{{ url('category',$row->slug) }}">{{ $row->title }}</a>
    @endforeach

@endsection


@extends('user_layout')
@section('content')
    <div class="container">
        <div class="shoes-grid">

            <div class="wrap-in">
                <div class="wmuSlider example1 slide-grid">
                    <div class="wmuSliderWrapper">
                        @foreach ($slider as $key => $value)
                            <article style="position: absolute; width: 100%; opacity: 0;">
                                <div class="banner-matter">
                                    <div class="col-md-5 banner-bag">
                                        <img class="img-responsive " src="public/uploads/slider/{{ $value->slider_image }}"
                                            alt=" " />
                                    </div>
                                    <div class="col-md-7 banner-off">
                                        <h2></h2>
                                        <label>{{ $value->slider_desc }}</label>
                                        <p></p>
                                        <a href="{{ URL::to('/all-product') }}">
                                            <span class="on-get" style="background-color: #98BAE7">Mua ngay</span>
                                        </a>
                                    </div>

                                    <div class="clearfix"> </div>
                                </div>

                            </article>
                        @endforeach
                    </div>

                    <ul class="wmuSliderPagination">
                        <li><a href="#" class="">0</a></li>
                        {{-- <li><a href="#" class="">1</a></li>
                <li><a href="#" class="">2</a></li> --}}
                    </ul>
                    <script src="{{ 'public/front/js/jquery.wmuSlider.js' }}"></script>
                    <script>
                        $('.example1').wmuSlider();
                    </script>
                </div>
            </div>
            </a>
            <!---->
            <div class="products">
                <h5 class="latest-product">Sản phẩm mới nhất</h5>
                <a class="view-all" href="{{ URL::to('/all-product') }}">Hiển thị tất cả<span> </span></a>
            </div>
            <div class="product-left">

                @foreach ($all_product as $key => $product)
                    <div class="col-md-3" style="border: 1px solid #ddd; border-radius:10px; margin: 33px">
                        <a href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_id) }}"><img
                                class="img-responsive chain"
                                src="{{ URL::to('public/uploads/product/' . $product->product_image) }}" alt=" " /></a>
                        <span class="star"> </span>
                        <div class="grid-chain-bottom">
                            <h6><a
                                    href="{{ URL::to('/chi-tiet-san-pham/' . $product->product_id) }}">{{ $product->product_name }}</a>
                            </h6>
                            <div class="star-price">
                                <div class="dolor-grid">
                                    <span class="actual">{{ number_format($product->product_price) }} đ</span>
                                </div>
                                {{-- <a class="now-get get-cart" style="margin-top: 20px; font-size: 12px;" href="#">Thêm vào giỏ
                                    hàng</a> --}}

                                <div class="clearfix"> </div>
                            </div>
                        </div>
                        <div class="grid-chain-bottom" style="font-size: 12px">
                        </div>
                    </div>
                @endforeach


                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    @endsection

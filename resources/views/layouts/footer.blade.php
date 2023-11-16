<footer class="site-footer bg-white border-top border-light">
    <section class="container container-max py-4">
        <div class="row small d-flex font-weight-light">
            <div class="col-md-3 col-sm-6 order-md-2 order-sm-1">
                <h6 class="text-brand">INFORMATION</h6>
                <ul class="list-unstyled">
                    <li class="my-2"><a href="{{ route('delivery.information') }}" class="text-dark">Delivery Information</a></li>
                    <li class="my-2"><a href="{{ route('discount') }}" class="text-dark">Discount</a></li>
                    <li class="my-2"><a href="{{ route('site.map') }}" class="text-dark">Sitemap</a></li>
                    <li class="my-2"><a href="{{ route('privacy.policy') }}" class="text-dark">Privacy Policy</a></li>

                </ul>
            </div>
            <div class="col-md-3 col-sm-6 order-md-3 order-sm-2">
                <h6 class="text-brand">MY ACCOUNT</h6>
                <ul class="list-unstyled">
                    <li class="my-2"><a href="{{ route('login') }}" class="text-dark">Sign In</a></li>
                    <li class="my-2"><a href="{{ route('profile') }}" class="text-dark">My Account</a></li>
                    <li class="my-2"><a href="{{ route('cart.show') }}" class="text-dark">View Cart</a></li>
                    <li class="my-2"><a href="{{ route('track.order') }}" class="text-dark">Track My Order</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-6 order-md-3 order-sm-2">
                <h6 class="text-brand">HELP</h6>
                <ul class="list-unstyled">
                    <li class="my-2"><a href="{{ route('faq') }}" class="text-dark">F.A.Q.</a></li>
                    <li class="my-2"><a href="{{ route('shipping') }}" class="text-dark">Shipping</a></li>
                    <li class="my-2"><a href="{{ route('contact.us') }}" class="text-dark">Contact Us</a></li>
                    <li class="my-2"><a href="{{ route('terms.conditions') }}" class="text-dark">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-6 order-md-3 order-sm-2">
                <h6 class="text-brand">CONTACT INFO</h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex py-1">
                        <i class="fa fa-fw mt-1 mr-2 fa-globe"></i> 
                        <span class="note-editor">{!! __siteMeta('address') !!}</span>
                    </li>
                    <li class="d-flex py-1">
                        <i class="fa fa-fw mt-1 mr-2 fa-phone-alt"></i> 
                        <a href="javascript:;" class="text-dark">{{ __siteMeta('contact') }}</a>
                    </li>
                    <li class="d-flex py-1">
                        <i class="fab fa-lg fa-fw mt-1 mr-1 fa-whatsapp text-dark"></i> 
                        <a href="javascript:;" class="text-dark">{{ __siteMeta('whatsapp') }}</a>
                    </li>
                    <li class="d-flex py-1">
                        <i class="fa fa-fw mt-1 mr-2 fa-envelope"></i> 
                        <a href="javascript:;" class="text-dark">{{ __siteMeta('email') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section class="bg-dark text-white p-2">
        <div class="container container-max d-sm-flex justify-content-between align-items-center text-sm-left text-center">
            <div class="small pb-sm-0 pb-2">Copyright 2019 BrandXpend.com all right reserved  -  Design by <a href="//www.brandxpend.com" target="_blank" class="text-white">Brandxpend</a></div>
            <figure class="m-0">
                <img src="{{ asset('images/payments.png') }}" alt="Payment Methods" class="img-fluid">
            </figure>
            {{-- <nav>
                <a href="javascript:;" class="text-white mx-1"><i class="fab fa-cc-visa fa-2x"></i></a>
                <a href="javascript:;" class="text-white mx-1"><i class="fab fa-cc-paypal fa-2x"></i></a>
                <a href="javascript:;" class="text-white mx-1"><i class="fab fa-amazon fa-2x"></i></a>
                <a href="javascript:;" class="text-white mx-1"><i class="fab fa-cc-mastercard fa-2x"></i></a>
                <a href="javascript:;" class="text-white mx-1"><i class="fab fa-cc-amex fa-2x"></i></a>
            </nav> --}}
        </div>
    </section>
</footer>

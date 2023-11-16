@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('styles/contact-us.css') }}">
@endpush

@section('content')
    @include('layouts.menu')
    <div class="container animated fadeIn">
            <h3 class="text-brand text-center p-5"> CONTACT US</h3>
        <div class="row">
            <div class="col-sm-6">
                <form action="{{ route('contact.us') }}" class="contact-form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required="">
                    </div>


                    <div class="form-group form_left">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="mobile" id="phone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="14" placeholder="Mobile No.">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control textarea-contact" rows="5" id="comment" name="message" placeholder="Type Your Message/Feedback here..." required=""></textarea>
                        <br>
                        <button class="btn btn-default btn-send"> <span class="fa fa-paper-plane"></span> Send </button>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                    <iframe width="100%" height="320px;" frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3592.0737281088245!2d55.966011814853495!3d25.8011416132644!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ef67131f14a49b1%3A0xd3b5ffc06b24ab09!2sRas%20Al%20Khaimah%20Economic%20Zone%20-%20RAKEZ!5e0!3m2!1sen!2sae!4v1579421320490!5m2!1sen!2sae" allowfullscreen></iframe>
                </div>
        </div>

        <div class="container mt-5">
            <div class="row">
                <!-- Boxes de Acoes -->
                <div class="col-xs-12 col-sm-6 col-lg-4">
                    <div class="box">
                        <div class="icon ">
                            <div class="image"><i class="fa fa-envelope contact-icons" aria-hidden="true"></i></div>
                            <div class="info">
                                <h3 class="title">MAIL & WEBSITE</h3>
                                <p>
                                    <i class="fa fa-envelope" aria-hidden="true"></i> &nbsp {{ __siteMeta('email') }}
                                    <br>
                                    <br>
                                    <i class="fa fa-globe" aria-hidden="true"></i> &nbsp www.brandxpend.com
                                </p>

                            </div>
                        </div>
                        <div class="space"></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4">
                    <div class="box">
                        <div class="icon">
                            <div class="image"><i class="fa fa-mobile contact-icons" aria-hidden="true"></i></div>
                            <div class="info">
                                <h3 class="title">CONTACT</h3>
                                <p>
                                    <i class="fa fa-mobile" aria-hidden="true"></i> &nbsp {{ __siteMeta('contact') }}
                                    <br>
                                    <br>
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i> &nbsp  {{ __siteMeta('whatsapp') }}
                                </p>
                            </div>
                        </div>
                        <div class="space"></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-lg-4">
                    <div class="box">
                        <div class="icon">
                            <div class="image"><i class="fa fa-map-marker contact-icons" aria-hidden="true"></i></div>
                            <div class="info">
                                <h3 class="title">ADDRESS</h3>
                                <p>
                                    <i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp {!! __siteMeta('address') !!}
                                </p>
                            </div>
                        </div>
                        <div class="space"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
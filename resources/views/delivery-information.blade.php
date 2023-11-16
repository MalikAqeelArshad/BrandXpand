@extends('layouts.app')

@section('title','Delivery Information')

@section('content')
    @include('layouts.menu')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center mt-4">Delivery Information</h3>
                <div class="mt-4 mb-5">
                    <p class="text-center">Once the order Delivery completed will be notified through registered mail with Invoice. <br />Any discrepancy on the invoice must be notified to us within 3 working days. </p>
                </div>
            </div>
        </div>
    </div>
@endsection
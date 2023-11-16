@extends('layouts.app')

@section('title','site-map')

@section('content')
    @include('layouts.menu')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center mt-4">Site Map</h3>
                <div class="mt-4 mb-5">
                    <p class="text-center">Vendor > Procurement > Stock Inventory > Order receipt > Order Process > Logistics > Customer > End User</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title','Shipping information')

@section('content')
@include('layouts.menu')
 <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center mt-4">Shipping Terms & Conditions</h3>
            <div class="mt-5 mb-3">
                <ul>
                    <li>Normal Delivery lead time within UAE 1-2 working days subject to stock & order confirmation.</li>
                    <li>Shipping cost will appear at the time of confirming the order.</li>
                    <li>Shipping cost will be calculated based on the country, State and Final Destination.</li>
                    <li>BrandXpend give option for self-collection without shipping cost. </li>
                    <li>BrandXpend provide free shipping depends on high value order based on Management approval.</li>
                    <li>Special shipping rates applicable for Bulk orders subject to the confirmation and approval.</li>
                    <li>For Express and outside to UAE shipping-Please contact our support desk via email on <span class="text-brand">info@brandxpend.com</span> <br />or whats up on <span class="text-brand">+97155 3800166</span> prior to order confirmation.</li>
                </ul>
            </div>
            </div>
        </div>
    </div>
@endsection
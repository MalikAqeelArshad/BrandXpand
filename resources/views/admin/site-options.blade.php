@extends('layouts.admin')

@section('title', 'Site Options')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Enter the address of website...',
            fontSize: 10,
            tabsize: 2,
            height: 150,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
            ]
        });
    });
</script>
@endpush

@section('content')
<!-- Page Content -->
<main class="content">

    <!--begin:: Flash Message -->
    @include('flash-message')
    <!--end:: Flash Message -->

    <form method="POST" action="{{ route('site.options') }}" class="alert alert-light shadow-sm border">
        @csrf @method('POST')
        <div class="modal-body">
            <h5 class="alert-heading text-brand font-weight-bold">WEBSITE INFO</h5>
            <small class="text-muted">Please set the website information.</small><hr>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') ?: __siteMeta('email') }}" class="form-control" placeholder="Enter the email of website" required>
            </div>
            <div class="form-group">
                <label>Contact</label>
                <input type="text" name="contact" value="{{ old('contact') ?: __siteMeta('contact') }}" class="form-control" placeholder="Enter the number of contact" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control summernote">{!! old('address') ?: __siteMeta('address') !!}</textarea>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') ?: __siteMeta('whatsapp') }}" class="form-control" placeholder="Enter the number of whatsapp">
                </div>
                <div class="form-group col-sm-6">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="{{ old('facebook') ?: __siteMeta('facebook') }}" class="form-control" placeholder="Enter the link of facebook">
                </div>
                <div class="form-group col-sm-6">
                    <label>Twitter</label>
                    <input type="text" name="twitter" value="{{ old('twitter') ?: __siteMeta('twitter') }}" class="form-control" placeholder="Enter the link of twitter">
                </div>
                <div class="form-group col-sm-6">
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram') ?: __siteMeta('instagram') }}" class="form-control" placeholder="Enter the link of instagram">
                </div>
                <div class="form-group col-sm-6">
                    <label>Linkedin</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin') ?: __siteMeta('linkedin') }}" class="form-control" placeholder="Enter the link of linkedin">
                </div>
                <div class="form-group col-sm-6">
                    <label>Youtube</label>
                    <input type="text" name="youtube" value="{{ old('youtube') ?: __siteMeta('youtube') }}" class="form-control" placeholder="Enter the link of youtube">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>Promotion</label>
                    <input type="text" name="promotion" value="{{ old('promotion') ?: __siteMeta('promotion') }}" class="form-control" placeholder="Enter the heading of promotion products">
                </div>
                <div class="form-group col-sm-6">
                    <label>Arrival</label>
                    <input type="text" name="arrival" value="{{ old('arrival') ?: __siteMeta('arrival') }}" class="form-control" placeholder="Enter the heading of arrival products">
                </div>
            </div>
        </div>
        <div class="modal-footer border-0 justify-content-start">
            <button type="reset" class="btn btn-light border"><small>Reset</small></button>
            <button type="submit" class="btn btn-info"><small>Save changes</small></button>
        </div>
    </form>
</main>
@endsection
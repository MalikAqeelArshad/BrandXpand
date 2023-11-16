@if ( ($slider = \App\Slider::where(['type'=>1, 'publish'=>true])->first()) && count($slides = $slider->slides()->wherePublish(true)->get()) )
<section class="slider">
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators mb-0">
            {{-- <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
            <li data-target="#carousel" data-slide-to="2"></li> --}}
            @foreach ($slides as $slide)
            <li data-target="#carousel" data-slide-to="{{ $loop->index }}" class="{{ ($loop->index == 0) ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner" style="max-height: 75vh">
            {{-- <div class="carousel-item active">
                <img src="{{ asset('images/slides/slide1.jpg') }}" class="w-100">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/slides/slide1.jpg') }}" class="w-100">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/slides/slide1.jpg') }}" class="w-100">
            </div> --}}
            @foreach ($slides as $slide)
            <div class="carousel-item {{ ($loop->index == 0) ? 'active' : '' }}">
                <a href="{{ make_slug($slide->url) }}" target="_blank">
                    <img src="{{ asset('uploads/sliders/'.$slide->gallery->filename) }}" class="w-100">
                </a>
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>
@endif
@if ( ($slider = \App\Slider::where(['type'=>2, 'publish'=>true])->first()) && count($slides = $slider->slides()->wherePublish(true)->get()) )
<section class="slider">
    <div id="carouselBanner" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators mb-1">
            @foreach ($slides as $slide)
            <li data-target="#carouselBanner" data-slide-to="{{ $loop->index }}" class="{{ ($loop->index == 0) ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner" style="max-height: 30vh">
            @foreach ($slides as $slide)
            <div class="carousel-item {{ ($loop->index == 0) ? 'active' : '' }}">
                <a href="{{ make_slug($slide->url) }}" target="_blank">
                    <img src="{{ asset('uploads/sliders/'.$slide->gallery->filename) }}" class="img-fluid">
                </a>
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselBanner" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselBanner" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>
@endif
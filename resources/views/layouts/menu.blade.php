<section class="main-menu sticky-top">
    <div class="container container-max">
        <nav class="navbar navbar-expand-md navbar-light p-0">
            <div class="collapse navbar-collapse mt-md-0 mt-2" id="navbarCollapse">
                <ul class="navbar-nav d-md-block">
                    @foreach(__all('Category') as $category)

                    @php $subCategories = $category->subCategories; @endphp

                    <li class="nav-item @if(count($subCategories)) dropdown @endif @if(count($subCategories) > 10) mega-menu @endif">
                        <a href="{{ count($subCategories) ? '#' : route('products', 'category='.$category->id) }}" 
                            class="nav-link @if(count($subCategories)) dropdown-toggle @endif" 
                            @if(count($subCategories)) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @endif>
                            {{ $category->name }}
                        </a>
                        @if(count($subCategories))
                        <div class="dropdown-menu shadow border-light">
                            <div class="row p-2">
                                @foreach($subCategories->split(ceil(count($subCategories) / 10 )) as $splitGroupSubCategories)
                                <nav class="col">
                                    @foreach($splitGroupSubCategories as $subCategory)
                                    <a class="dropdown-item" href="{{ route('products', ['sub-category'=>$subCategory->id]) }}">
                                        {{ $subCategory->name }}
                                    </a>
                                    @endforeach
                                </nav>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</section>
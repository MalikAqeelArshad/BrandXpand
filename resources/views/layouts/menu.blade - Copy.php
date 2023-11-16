<section class="main-menu sticky-top">
    <div class="container container-max">
        @foreach(__all('Category')->split(ceil(__all('Category')->count() / 9)) as $row)
            <nav class="navbar navbar-expand-md navbar-light p-0">
                <div class="collapse navbar-collapse mt-md-0 mt-2" id="navbarCollapse">
                    <ul class="navbar-nav d-md-block">
                        @foreach($row as $category)
                            <li class="nav-item @if(sizeof($category->subcategories) > 10) dropdown mega-menu @endif">
                                <a class="nav-link dropdown-toggle @if(sizeof($category->subcategories) == 0) removeCaret @endif" 
                                    href="@if(sizeof($category->subcategories) == 0) {{ route('category.products',$category->id) }} @else # @endif"  
                                    @if(sizeof($category->subcategories) > 0) data-toggle="dropdown" @endif aria-haspopup="true" aria-expanded="false">
                                    {{ $category->name }}
                                </a>
                                @if(sizeof($category->subcategories) > 0)
                                    <div class="dropdown-menu shadow border-0 rounded-0 p-0">
                                        <div class="row p-2">
                                            @foreach($category->subcategories->split(ceil($category->subcategories->count() / 10 )) as $cat)
                                                <nav class="col">
                                                    @foreach($cat as $subCategory)
                                                        <a class="dropdown-item" href="{{ route('category.detail',$subCategory->id) }}">
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
        @endforeach
    </div>
</section>
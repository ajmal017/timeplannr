@include('header')

<!-- BOOKS COLLECTION -->
<div id="books" class="wrapper">
    <div class="bks-title-box">
        <h1>Search results</h1>
    </div>
    <div id="books--collection">
        <ul class="books">
            <?php $modulo = 3; $i = 0; ?>
            @loop
            <li <?php if($modulo - 1 == $i % $modulo ){ echo('class="last"'); } ?>>
                <div class="book">
                    <h3>{{ Loop::title() }}</h3>
                    <a href="{{ Loop::link() }}" class="book-featured-box" style="background-color: {{ Meta::get(Loop::id(), 'color') }};">
                        {{ Loop::thumbnail() }}
                    </a>
                    <p>{{ Loop::excerpt() }}</p>
                    <div class="button-box">
                        <a href="/venue/plan?id={{ Loop::id() }}" class="tiny-button">Plan time</a>
                    </div>
                </div>
            </li>
            <?php $i++; ?>
            @endloop
        </ul>
        @if(!have_posts())
            <h2>Sorry, but we can't find any venues like that</h2>
        @endif
    </div>
</div>
<!-- END BOOKS COLLECTION -->

@include('footer')
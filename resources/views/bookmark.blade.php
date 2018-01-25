<article class="row">
    
    <time datetime="{{ $bookmark->time_posted->format("j F Y H:i") }}" title="{{ $bookmark->time_posted->format("j F Y H:i") }}">{{ $bookmark->time_posted->diffForHumans() }}</time>
    
    
    <div class="definition">
        <a href="{{ $bookmark->url }}">
            <h2>{{ $bookmark->title }}</h2>
            <span class="url"><strong>{{ $bookmark->rootUrl }}</strong>{{ parse_url($bookmark->url, PHP_URL_PATH) }}</span>
        </a>

        @if(!empty($bookmark->description))
            <p class="details">
                {{ $bookmark->description }}
            </p>
        @endif
        
        @if(count($bookmark->tags) > 1)
            <ul class="tags">
                <li><i data-feather="tag"><span class="sr-only">Tag</span></i></li>
                @foreach($bookmark->tags as $tag)
                    <?php if($tag == 'via:popular') continue; ?>
                     <li><a href="{{ action("BookmarkController@showTag", ['tag' => $tag->tag ]) }}">{{ $tag }}<span class="sr-only">, tag</a></li>
                @endforeach
            </ul>
        @endif
    </div>
</article>

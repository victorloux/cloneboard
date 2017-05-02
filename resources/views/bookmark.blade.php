<article>
    <aside>
        <time title="{{ $bookmark->time_posted->format("j F Y H:i") }}">{{ $bookmark->time_posted->diffForHumans() }}</time>
    </aside>
    
    <div class="definition">
        <h4><a href="{{ $bookmark->url }}">{{ $bookmark->title }}</a></h4>
        <h5><a href="{{ $bookmark->url }}"><strong>{{ $bookmark->rootUrl }}</strong>{{ parse_url($bookmark->url, PHP_URL_PATH) }}</a></h5>

        @if(!empty($bookmark->description))
            <p class="details">
                {{ $bookmark->description }}
            </p>
        @endif
        
        <ul class="tags">
            <li>Tags</li>
            @foreach($bookmark->tags as $tag)
                <?php if($tag == 'via:popular') continue; ?>
                 <li><a href="{{ action("BookmarkController@showTag", ['tag' => $tag->tag ]) }}">{{ $tag}}</a></li>
            @endforeach
        </ul>
    </div>
</article>

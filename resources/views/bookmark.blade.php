<div class="row bookmark">
    <?php
    $lang = '';
    if($bookmark->tags->contains('tag', 'lang:fr')) {
        $lang = ' lang="fr"';
    }
    ?>
    <div class="definition">
        <a href="{{ $bookmark->url }}"{!! $lang !!}>
            <h2>{{ $bookmark->title }}</h2>
            {{-- role=text to avoid a bug with VoiceOver separating strong --}}
            <span class="url" role="text"><strong>{{ $bookmark->rootUrl }}</strong>{{ parse_url($bookmark->url, PHP_URL_PATH) }}</span>
        </a>

        @if(!empty($bookmark->description))
            <blockquote{!! $lang !!}>
                {{ $bookmark->description }}
            </blockquote>
        @endif
        
        @if(count($bookmark->tags) > 1)
            <ul class="tags">
                <li><svg class="feather" aria-hidden="true">
                    <title>Tag:</title>
                    <use xlink:href="{{ env("SVG_ROOT", "/feather-sprite.svg") }}#tag"/>
                </svg></li>
                @foreach($bookmark->tags as $tag)
                    <?php if($tag == 'via:popular') continue; ?>
                     <li><a href="{{ action("BookmarkController@showTag", ['tag' => $tag->tag ]) }}">{{ $tag }}<span class="sr-only">, tag</a></li>
                @endforeach
                <li><span aria-hidden="true">&middot;</span><time datetime="{{ $bookmark->time_posted->format("j F Y H:i") }}" title="{{ $bookmark->time_posted->format("j F Y H:i") }}">{{ $bookmark->time_posted->diffForHumans() }}</time>
                </li>
            </ul>
        @endif
    </div>
</div>

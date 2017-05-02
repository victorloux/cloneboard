<article>
     <h3><a href="{{ $bookmark->url }}">{{ $bookmark->title }}</a></h3>

     <div class="description">
         {{ $bookmark->description }}
     </div>


     <ul class="tags">
         @foreach($bookmark->tags as $tag)
             <?php if($tag == 'via:popular') continue; ?>
             <li><a href="{{ action("BookmarkController@showTag", ['tag' => $tag->tag ]) }}">{{ $tag }}</a></li>
         @endforeach
     </ul>
     <div class="url"><a href="{{ $bookmark->url }}">{{ $bookmark->rootUrl }}</a> &middot; {{ $bookmark->time_posted->diffForHumans() }}</div>

</article>

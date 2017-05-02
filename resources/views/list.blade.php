<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Victor Loux — bookmarks</title>
    </head>
    <body>
        @if(isset($tagName))
            <h2>Bookmarks tagged <strong>{{ $tagName }}</strong></h2>
        @endif
        <section class="bookmarks">
           @foreach($bookmarks as $bookmark)
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
            @endforeach
        </section>
        <section class="pagination">
            {{ $bookmarks->links() }}
        </section>
    </body>
</html>

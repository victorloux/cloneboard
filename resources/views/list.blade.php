<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="//use.typekit.net/tik4qco.js"></script>
        <script type="text/javascript">
            try{Typekit.load();}catch(e){}
        </script>
        <link rel="stylesheet" href="{{ env("CSS_ROOT", "css/master.css") }}">

        <title>Victor Loux — bookmarks</title>
    </head>
    <body>
        <header>
            <h1><a href="/">Victor Loux</a> <span class="separator">&rarr;</span>
                @if(isset($tagName) || isset($query))
                    <a href="/bookmarks/">Bookmarks</a>
                @else
                    Bookmarks
                @endif
            </h1>
        </header>
        
        <section class="intro">
            <p class="interests">
                A feed of <strong>interesting articles and websites</strong> I have recently read.
                Please feel free to browse and <a href="mailto:io@victorloux.uk">send me suggestions</a> of sites to look at — I’m curious about a lot of things!
            </p>
        </section>


        @if(isset($tagName))
            <h2>Bookmarks tagged <strong>{{ $tagName }}</strong></h2>
        @elseif(isset($query))
            <h2>{{ $resultsCount }} results for “<strong>{{ $query }}</strong>”</h2>
        @endif
        
        @if($bookmarks->currentPage() > 1)
            {{ $bookmarks->links() }}
        @endif

        <section class="cv bookmarks">
           @foreach($bookmarks as $bookmark)
               @include("bookmark")
            @endforeach
        </section>

        {{ $bookmarks->links() }}
        
        <section class="extra">
            <p>{{ number_format($totalBookmarks) }} bookmarks total &middot; Imported from <a href="https://pinboard.in/u:vloux">Pinboard</a></p>
            
            <form action="{{ action("BookmarkController@searchForm") }}" id="search" method="POST">
                <input type="text" name="query" value="{{ old('query') }}"><input type="submit" name="search" value="search">
                {{ csrf_field() }}
            </form>
            </p>
        </section>
    </body>
</html>

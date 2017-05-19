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
        <div class="container">
            <header>
                <h1 class="eight columns offset-by-two">
                    <a href="/">Victor Loux</a> <span class="separator">&rarr;</span>
                    @if(isset($tagName) || isset($query))
                        <a href="/bookmarks/">Bookmarks</a>
                    @else
                        Bookmarks
                    @endif
                </h1>
            
                <section class="row intro">
                    <p class="interests seven columns offset-by-two">
                        A feed of <strong>interesting articles and websites</strong> I have recently read.
                        Please feel free to browse and <a href="mailto:io@victorloux.uk">send me suggestions</a> of sites to look at — I’m curious about a lot of things!
                    </p>
                </section>
            </header>


            @if(isset($tagName))
                @if($resultsCount == 0)
                    <h2 class="eight columns offset-by-two">No results for {{ $tagName }}!</h2>
                @else
                    <h2 class="eight columns offset-by-two">{{ $resultsCount }} {{ str_plural("bookmark", $resultsCount) }} tagged “<strong>{{ $tagName }}</strong>”</h2>
                @endif
            @elseif(isset($query))
                @if($resultsCount == 0)
                    <h2 class="eight columns offset-by-two">No results for {{ $query }}!</h2>
                @else
                    <h2 class="eight columns offset-by-two">{{ $resultsCount }} {{ str_plural("result", $resultsCount) }} results for “<strong>{{ $query }}</strong>”</h2>
                @endif
            @endif
            
            @if($bookmarks->currentPage() > 1)
                <div class="row eight columns offset-by-two">{{ $bookmarks->links() }}</div>
            @endif

            <section class="cv bookmarks">
               @foreach($bookmarks as $bookmark)
                   @include("bookmark")
                @endforeach
            </section>

            <div class="row eight columns offset-by-two">{{ $bookmarks->links() }}</div>
            
            <footer class="extra eight columns offset-by-two">
                <p>{{ number_format($totalBookmarks) }} bookmarks total &middot; Imported from <a href="https://pinboard.in/u:vloux">Pinboard</a></p>
                
                <form action="{{ action("BookmarkController@searchForm") }}" class="search" method="POST" aria-role="search">
                    <input type="text" name="query" value="{{ old('query') }}" placeholder="Tag or keyword"><input type="submit" name="search" value="search">
                    {{ csrf_field() }}
                </form>
                </p>
            </footer>
        </div>
    </body>
</html>

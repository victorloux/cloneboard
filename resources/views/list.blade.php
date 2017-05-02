<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Victor Loux — bookmarks</title>
    </head>
    <body>
        <form action="{{ action("BookmarkController@searchForm") }}" id="search" method="POST">
            <input type="text" name="query" value="{{ old('query') }}">
            <input type="submit" name="search" value="search">
            {{ csrf_field() }}
        </form>

        @if(isset($tagName))
            <h2>Bookmarks tagged <strong>{{ $tagName }}</strong></h2>
        @elseif(isset($query))
            <h2>Searching for “<strong>{{ $query }}</strong>”</h2>
            <p>{{ $resultsCount }} results.</p>
        @endif

        <section class="bookmarks">
           @foreach($bookmarks as $bookmark)
               @include("bookmark")
            @endforeach
        </section>
        <section class="pagination">
            {{ $bookmarks->links() }}
        </section>
    </body>
</html>

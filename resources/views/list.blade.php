<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="{{ env("CSS_ROOT", "css/master.css") }}">

        <title>Victor Loux — bookmarks
            @if(isset($tagName))
                tagged “{{ $tagName }}”
            @elseif(isset($query))
                — Search: “{{ $query }}”
            @endif
        </title>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            let curr = -1;
            let links = document.querySelectorAll('.definition > a');
            let max = links.length - 1;
            document.addEventListener("keyup", function(ev) {
                if(ev.key == 'j') {
                    if(curr == max) return;
                    curr++;
                    links[curr].focus();
                } else if (ev.key == 'k') {
                    if(curr <= 0) return;
                    curr--;
                    links[curr].focus();
                }
            });
        });
        </script>
        {{-- <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                class list {
                    constructor(domElement) {
                        this.tags = domElement.querySelectorAll("a");
                        this.makeFirstSelectable();
                        this.maxIndex = tags.length;
                    }
                    
                    makeFirstSelectable() {
                        this.tags.forEach(function(tag, idx) {
                            if(idx == 0) return;
                            tag.tabIndex = -1;
                        });
                        this.currentIndex = 0;
                    }
                }
                let lists = [];

                let listsInDOM = document.querySelectorAll(".tags");
                listsInDOM.forEach(function(el) {
                    lists.push(new list(el))
                });
                
                // let selectableTags = document.querySelectorAll(".tags a:not([tabindex='-1'])");
                // selectableTags.forEach(function(el) {
                //     el.addEventListener("focus", function() {
                //         // get current index, total indices
                //         console.log(this.innerHTML);
                //         let ul = this.parentElement.parentElement;
                //         ul.querySelectorAll("li");
                //     });
                // });
            })
        </script> --}}
    </head>
    <body class="bookmarks">
        <div class="container">
            <header class="row">
                <h1>
                    <a href="/">Victor Loux</a>
                    <span class="separator">&rarr;</span>
                    @if(isset($tagName) || isset($query))
                        <a href="/bookmarks/">Bookmarks</a>
                        <span class="separator">&rarr;</span>
                        @if(isset($tagName))
                            <svg class="feather" viewBox="0 0 24 24">
                                <title>Tag:</title>
                              <use xlink:href="{{ env("SVG_ROOT", "/feather-sprite.svg") }}#tag"/>
                            </svg>
                            {{ $tagName }}
                        @elseif(isset($query))
                            <svg class="feather" viewBox="0 0 24 24">
                              <use xlink:href="{{ env("SVG_ROOT", "/feather-sprite.svg") }}#search"/>
                            </svg>
                            Search: “{{ $query }}”
                        @endif
                    @else
                        Bookmarks
                    @endif
                </h1>
            
                <p class="intro row">
                @if(isset($tagName))
                    @if($resultsCount == 0)
                        No results for {{ $tagName }}.
                    @else
                        {{ $resultsCount }} {{ str_plural("bookmark", $resultsCount) }} tagged “<strong>{{ $tagName }}</strong>”
                    @endif
                @elseif(isset($query))
                    @if($resultsCount == 0)
                        No results for <strong>{{ $query }}</strong>.
                    @else
                        {{ $resultsCount }} {{ str_plural("result", $resultsCount) }} results for “<strong>{{ $query }}</strong>”
                    @endif
                @else
                    A feed of <strong>interesting articles and websites</strong> I have recently read.
                    Please feel free to browse and <a href="mailto:io@victorloux.uk">send me suggestions</a> of sites to look at — I’m curious about a lot of things!
                @endif
                </p>
                
                <form action="{{ action("BookmarkController@searchForm") }}" method="POST" role="search">
                    <input type="text" name="query" value="{{ old('query') }}" placeholder="Find a tag or keyword&hellip;" aria-label="Search for a tag or keyword"><input type="submit" name="search" value="Search">
                    {{ csrf_field() }}
                </form>
            </header>

            @if($bookmarks->currentPage() > 1)
                <div class="row eight columns offset-by-two">{{ $bookmarks->links() }}</div>
            @endif

            <main class="cv bookmarks">
               @foreach($bookmarks as $bookmark)
                   @include("bookmark")
                @endforeach
            </main>

            <div class="row eight columns offset-by-two" role="nav" aria-label="Pagination">{{ $bookmarks->links() }}</div>
            
            <footer>
                {{ number_format($totalBookmarks) }} bookmarks total &middot; Imported from <a href="https://pinboard.in/u:vloux">Pinboard</a>
            </footer>
        </div>
    </body>
</html>

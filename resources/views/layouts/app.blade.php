<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    @php do_action('get_header') @endphp
    @include('partials.header')
    <div class="wrap container" role="document">
      @if (App\display_sidebar())
        <div class="content row">
          <main class="main col-md-8">
            @yield('content')
          </main>
          <aside class="sidebar col-md-4">
            @include('partials.sidebar')
          </aside>
        </div>
      @else
        <div class="content row">
          <main class="main col-md-12">
            @yield('content')
          </main>
        </div>
      @endif
    </div>
    @php do_action('get_footer') @endphp
    @include('partials.footer')
    @php wp_footer() @endphp
  </body>
</html>

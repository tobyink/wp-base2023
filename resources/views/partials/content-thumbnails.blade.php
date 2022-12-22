<article @php post_class("row mb-4 bg-light") @endphp>
  <div class="col-lg-6 p-0">
    <a href="{{ get_permalink() }}">@php the_post_thumbnail( 'medium-rect', [ 'class' => 'w-100 h-auto' ] ) @endphp</a>
  </div>
  <div class="col-lg-6">
    <header class="py-2">
      <h2 class="entry-title"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
    </header>
    <div class="entry-summary">
      @php the_excerpt() @endphp
    </div>
  </div>
</article>

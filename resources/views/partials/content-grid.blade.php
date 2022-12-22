<article @php post_class("col-md-6 d-flex flex-column") @endphp>
  <a href="{{ get_permalink() }}">@php the_post_thumbnail( 'medium-rect', [ 'class' => 'w-100 h-auto' ] ) @endphp</a>
  <div class="p-3 pb-0 bg-light flex-grow-1">
    <header class="py-2">
      <h2 class="entry-title"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
    </header>
    <div class="entry-summary">
      @php the_excerpt() @endphp
    </div>
  </div>
  <div class="p-3 pt-0 bg-light text-center">
    <a class="btn btn-primary btn-sm" href="{{ get_permalink() }}">Read More</a>
  </div>
</article>

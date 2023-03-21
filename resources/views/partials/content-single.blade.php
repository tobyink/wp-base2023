<article @php post_class() @endphp>
  @if ( ! get_field( 'hide_title', \App\acf_page_id() ) )
    <header>
      <h1 class="entry-title">{!! get_the_title() !!}</h1>
      @include('partials/entry-meta')
    </header>
  @endif
  @if ( has_post_thumbnail() && ! get_field( 'hide_featured_image', \App\acf_page_id() ) )
    <div class="the-post-thumbnail">
      @php the_post_thumbnail( 'large' ) @endphp
    </div>
  @endif
  <div class="entry-content">
    @php the_content() @endphp
  </div>
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
  @php comments_template('/partials/comments.blade.php') @endphp
</article>

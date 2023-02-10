<div class="article-meta">

<p>
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
</p>

@php
  $author_id = get_the_author_meta('ID');
  $author_url = get_the_author_meta('user_url', $author_id);
  if ( ! $author_url ) {
    $author_url = get_author_posts_url($author_id);
  }
@endphp

<p class="byline author vcard">
  {{ __('By', 'sage') }} <a href="{{ $author_url }}" rel="author" class="fn">
    {{ get_the_author() }}
  </a>
</p>

<p class="share-link">
  <a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share this article</a>
</p>

</div>

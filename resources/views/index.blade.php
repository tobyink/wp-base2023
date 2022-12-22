@extends('layouts.app')

@php
  $parsley_blog_style = get_field( 'parsley_blog_style', 'options' );
@endphp

@section('content')
  @include('partials.page-header')

  @if (have_posts())
    <div class="blog-style-{{ $parsley_blog_style }} @php if ( $parsley_blog_style == 'grid' ) { echo 'row'; } @endphp">
    @while (have_posts()) @php the_post() @endphp
      @includeFirst(["partials.content-${parsley_blog_style}-" . get_post_type(), 'partials.content-' . get_post_type(), 'partials.content'])
    @endwhile
    </div>
  @else
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>
    {!! get_search_form(false) !!}
  @endif

  {!! \App\bootstrap_pagination() !!}
@endsection

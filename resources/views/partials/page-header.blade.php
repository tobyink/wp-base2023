@if ( ! get_field( 'hide_title', \App\acf_page_id() ) )
<div class="page-header">
  <h1>{!! $title !!}</h1>
</div>
@endif


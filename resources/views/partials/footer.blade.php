@if ( \App\theme_get_option('scroll-to-top') )
<a href="#" id="return-to-top"><i class="fa fa-arrow-up"></i></a>
<script type="text/javascript">jQuery('#return-to-top').hide();</script>
@endif

@if ( ! get_field( 'hide_footer', \App\acf_page_id() ) )
<footer class="content-info">
  <div class="container">
    @php dynamic_sidebar('sidebar-footer') @endphp
  </div>
</footer>
@endif

<?php global $RTags; ?>
  <footer id="global-footer">
    <div class="copyright">&copy; 2017 <?php $RTags->siteinfo('title'); ?>.</div>
  </footer>

<audio id="play-se" preload="auto">
  <source src="<?php echo $RTags::$assets_path; ?>sound/short.wav" type="audio/wav">
</audio>
<?php wp_footer(); ?>

</body>
</html>

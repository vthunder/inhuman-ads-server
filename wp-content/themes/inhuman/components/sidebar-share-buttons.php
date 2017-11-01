<section class="sidebar-wrapper sidebar3 share-buttons-sidebar">
  <div class="sidebar">
    <h3>Share Bad Ads</h3>
    <div class="share-buttons">
      <?php
        $url = 'https://inhumanads.com/';
        $fb = 'http://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) .
              '&title=' . urlencode('Check out Inhuman Ads!');
        $tw = 'http://twitter.com/intent/tweet?status=' . urlencode("Check out Inhuman Ads! $url");
      ?>
      <a href="<?php echo $fb; ?>" target="_blank"
         class="button share-facebook">
        <i class="fa fa-facebook"></i> Share us on Facebook
      </a>
      <a href="<?php echo $tw; ?>" target="_blank"
         class="button share-twitter">
        <i class="fa fa-twitter"></i> Tweet about us
      </a>
    </div>
  </div>
</section>

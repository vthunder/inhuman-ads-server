<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/page/page.css"); ?>
<?php get_header('page'); ?>

<section class="title small">
  <div class="title-inner">
    <img class="clouds cloud-left" src="<?php tpldir(); ?>/assets/clouds-left.png">
    <h1 class="logo">About<br>Inhuman Ads</h1>
    <img class="clouds cloud-right" src="<?php tpldir(); ?>/assets/clouds-right.png">
  </div>
</section>

<div class="content-wrapper">

  <div class="sidebars">
    <?php include('components/sidebar-faq.php'); ?>
    <?php include('components/sidebar-latest-blog-posts.php'); ?>
  </div>

  <section class="main-wrapper main-content">
    <div class="main">
      <h2>What is an inhuman ad?</h2>

      <p>When you see an online ad and you can tell there’s no way a real live person would’ve published it, you’re looking at an inhuman ad. It could be something ridiculous like an ad for fried chicken with a story about a massive poultry recall. Or it could be utterly tone deaf like a local jeweler advertising beside a picture of a starving child. It’s the natural outcome of an online advertising marketplace mostly run by <a href="https://en.wikipedia.org/wiki/Real-time_bidding">money</a> and <a href="https://en.wikipedia.org/wiki/Ad_exchange">robots</a>.</p>

      <p>Inhuman Ads is a space for humans to clean up the mess made by automated platforms and advertiser dollars. Brought to you by Mozilla, this is a forum for you to share the funny, frustrating, and infuriating ad placements you find in your online adventures. It’s also a community where you can see the inhuman ads spotted by other fellow travelers and upvote the best (worst?) ones. Laugh, cry, or laugh-cry at the tragic absurdity of the modern web. By sharing these poor ad placements (plus the URLs where they came from — receipts!) we are creating a forum for advertisers and publishers to face their audience and own the flaws of a broken system.</p>

      <p>We’re all about accountability but we’re definitely not here to make enemies. Mozilla, through Inhuman Ads, envisions a healthier ad ecosystem where end users, publishers, and advertisers collectively push for better content and experiences by first identifying the problem. When you see equality fundraisers promoted on xenophobic blogs or adult content served on family websites, you don’t have to resign yourself to thinking, well, that’s just the Internet. Mozilla believes that with your involvement, bad ads can become a thing of the past.</p>

      <p>Ready to jump in and help? <a href="/contribute">Contribute your own ad</a>.</p>
    </div>
  </section>

</div>

<?php get_footer(); ?>

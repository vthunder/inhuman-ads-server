<?php
  $excluded_users = array_merge(
    inhuman_anon_user_ids(),
    inhuman_users_with_display_name('wpengine'));
  $day_users = get_users(array(
    'meta_key' => 'inhuman_user_score_today',
    'meta_value' => 0,
    'meta_compare' => '>',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'exclude' => $excluded_users,
    'number' => 10
  ));
  $week_users = get_users(array(
    'meta_key' => 'inhuman_user_score_week',
    'meta_value' => 0,
    'meta_compare' => '>',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'exclude' => $excluded_users,
    'number' => 10
  ));
  $forever_users = get_users(array(
    'meta_key' => 'inhuman_user_score_forever',
    'meta_value' => 0,
    'meta_compare' => '>',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'exclude' => $excluded_users,
    'number' => 10
  ));
?>
<?php get_header(); ?>
<?php wp_enqueue_style('post', get_template_directory_uri() . "/styles/post.css"); ?>
<?php wp_enqueue_style('page', get_template_directory_uri() . "/styles/page.css"); ?>
<?php get_sidebar(); ?>

<div class="page-body">
  <h1 class="page-title">High Scores</h1>

  <br><br>
  <h3>Today</h3>
  <table class="highscore-table">
    <tr>
      <th class="user-header">User</th>
      <th class="score-header">Score</th>
    </tr>
    <?php foreach ($day_users as $user): ?>
      <tr>
        <td class="highscore-user"><?php echo $user->display_name; ?></td>
        <td class="highscore-score"><?php echo get_user_meta($user->ID, 'inhuman_user_score_today', true); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <br><br>
  <h3>This week</h3>
  <table class="highscore-table">
    <tr>
      <th class="user-header">User</th>
      <th class="score-header">Score</th>
    </tr>
    <?php foreach ($week_users as $user): ?>
      <tr>
        <td class="highscore-user"><?php echo $user->display_name; ?></td>
        <td class="highscore-score"><?php echo get_user_meta($user->ID, 'inhuman_user_score_week', true); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <br><br>
  <h3>All time</h3>
  <table class="highscore-table">
    <tr>
      <th class="user-header">User</th>
      <th class="score-header">Score</th>
    </tr>
    <?php foreach ($forever_users as $user): ?>
      <tr>
        <td class="highscore-user"><?php echo $user->display_name; ?></td>
        <td class="highscore-score"><?php echo get_user_meta($user->ID, 'inhuman_user_score_forever', true); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php get_footer('post'); ?>

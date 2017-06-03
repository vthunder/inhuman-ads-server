<?php
/*
   Plugin Name: Inhuman Ads
 */

abstract class Inhuman_Meta_Box
{
    public static function add()
    {
        $screens = ['post', 'inhuman_card'];
        foreach ($screens as $screen) {
            add_meta_box(
                'inhuman_box_id',          // Unique ID
                'Inhuman Ads Options', // Box title
                [self::class, 'html'],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }
    
    public static function save($post_id)
    {
        if (array_key_exists('inhuman_field_type', $_POST)) {
            update_post_meta(
                $post_id,
                '_inhuman_meta_key_type',
                $_POST['inhuman_field_type']
            );
        }
        if (array_key_exists('inhuman_field_featured', $_POST)) {
            update_post_meta(
                $post_id,
                '_inhuman_meta_key_featured',
                $_POST['inhuman_field_featured']
            );
        }
        if (array_key_exists('inhuman_field_sort', $_POST)) {
            update_post_meta(
                $post_id,
                '_inhuman_meta_key_sort',
                $_POST['inhuman_field_sort']
            );
        }
        if (array_key_exists('inhuman_field_height', $_POST)) {
            update_post_meta(
                $post_id,
                '_inhuman_meta_key_height',
                $_POST['inhuman_field_height']
            );
        }
        if (array_key_exists('inhuman_field_width', $_POST)) {
            update_post_meta(
                $post_id,
                '_inhuman_meta_key_width',
                $_POST['inhuman_field_width']
            );
        }
    }
    
    public static function html($post)
    {
        $value_type = get_post_meta($post->ID, '_inhuman_meta_key_type', true);
        $value_featured = get_post_meta($post->ID, '_inhuman_meta_key_featured', true);
        $value_sort = get_post_meta($post->ID, '_inhuman_meta_key_sort', true);
        $value_height = get_post_meta($post->ID, '_inhuman_meta_key_height', true);
        $value_width = get_post_meta($post->ID, '_inhuman_meta_key_width', true);
?>

    <style type="text/css">
     .inhuman_meta_block {
         margin-bottom: 20px;
     }
     .inhuman_meta_select_block label {
         display: inline-block;
         width: 6em;
         padding-bottom: 16px;
     }
    </style>


    <div class="inhuman_meta_select_block">
        <label for="inhuman_field_type">Card Type</label>
        <select name="inhuman_field_type" id="inhuman_field_type" class="postbox">
            <option value="">Post</option>
            <option value="plain" <?php selected($value_type, "plain"); ?>>Plain</option>
            <option value="background" <?php selected($value_type, "background"); ?>>Background (no card)</option>
        </select>
    </div>

    <div class="inhuman_meta_block">
        <input type="checkbox" name="inhuman_field_featured" id="inhuman_field_featured" <?php checked($value_featured, "on"); ?>>
        <label for="inhuman_field_featured">Featured</label>
    </div>

    <div class="inhuman_meta_select_block">
        <label for="inhuman_field_sort">Sort order</label>
        <select name="inhuman_field_sort" id="inhuman_field_sort" class="postbox">
            <option value="">Don't Pin</option>
            <option value="1" <?php selected($value_sort, 1); ?>>1</option>
            <option value="2" <?php selected($value_sort, 2); ?>>2</option>
            <option value="3" <?php selected($value_sort, 3); ?>>3</option>
            <option value="4" <?php selected($value_sort, 4); ?>>4</option>
            <option value="5" <?php selected($value_sort, 5); ?>>5</option>
            <option value="6" <?php selected($value_sort, 6); ?>>6</option>
            <option value="7" <?php selected($value_sort, 7); ?>>7</option>
            <option value="8" <?php selected($value_sort, 8); ?>>8</option>
            <option value="9" <?php selected($value_sort, 9); ?>>9</option>
            <option value="10" <?php selected($value_sort, 10); ?>>10</option>
        </select>
    </div>

    <div class="inhuman_meta_select_block">
        <label for="inhuman_field_width">Card Width</label>
        <select name="inhuman_field_width" id="inhuman_field_width" class="postbox">
            <option value="">Standard</option>
            <option value="wide" <?php selected($value_width, 'wide'); ?>>Wide</option>
            <option value="wide2" <?php selected($value_width, 'wide2'); ?>>Extra-wide</option>
        </select>
    </div>

    <div class="inhuman_meta_select_block">
        <label for="inhuman_field_height">Card Height</label>
        <select name="inhuman_field_height" id="inhuman_field_height" class="postbox">
            <option value="">Standard</option>
            <option value="short" <?php selected($value_height, 'short'); ?>>Short</option>
            <option value="tall" <?php selected($value_height, 'tall'); ?>>Tall</option>
        </select>
    </div>
<?php
}
}

add_action('add_meta_boxes', ['Inhuman_Meta_Box', 'add']);
add_action('save_post', ['Inhuman_Meta_Box', 'save']);

add_filter('the_content', 'inhuman_content_filter', 9);
function inhuman_content_filter($content) {
    global $post;
    $type = get_post_meta($post->ID, '_inhuman_meta_key_type', true);
    if ("plain" == $type or "background" == $type) {
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
    }
    return $content;
}

?>

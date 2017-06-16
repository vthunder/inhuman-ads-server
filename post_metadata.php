<?php

//
// Custom metadata
//
abstract class Inhuman_Meta {

    public static $keys = array(
        'type', 'featured', 'sort', 'width', 'height', 'screenshot'
    );

    public static function get_meta($post_id) {
        $meta = array();
        foreach (self::$keys as $key) {
            $meta[$key] = get_post_meta($post_id, '_inhuman_meta_' . $key, true);
        }
        return $meta;
    }

    public static function add() {
        $screens = ['post', 'inhuman_screenshot'];
        foreach ($screens as $screen) {
            add_meta_box(
                'inhuman_box_id',          // Unique ID
                'Inhuman Ads Options', // Box title
                [self::class, 'html'],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }
    
    public static function save($post_id) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['inhuman_meta_nonce'], basename(__FILE__) ) ) {
	    return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	    return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['post_type'] ) {
	    if ( !current_user_can( 'edit_page', $post_id ) ) {
		return $post_id;
	    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	    }  
	}

        $new = $_POST['inhuman_meta'];
        $old = self::get_meta($post_id);

        foreach (self::$keys as $key) {
            if ($new[$key] && $new[$key] !== $old[$key]) {
                update_post_meta($post_id, '_inhuman_meta_' . $key, $new[$key]);
            } elseif (!$new[$key] && $old[$key]) {
                delete_post_meta($post_id, '_inhuman_meta_' . $key, $old[$key]);
            }
        }
    }
    
    public static function html($post) {
	$meta = Inhuman_Meta::get_meta($post->ID); ?>

    <input type="hidden" name="inhuman_meta_nonce"
           value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- All fields will go here -->
    <style type="text/css">
     .inhuman_label {
         display: inline-block;
         width: 6em;
     }
     .inhuman_fieldset {
         border-top: 1px solid #ddd;
         margin: 1em 0 1em;
     }
     .inhuman_fieldset > * {
         margin-left: 2em;
     }
     .inhuman_fieldset legend {
         font-weight: bold;
         margin-left: .75em;
         padding: 0 .5em 0 .5em;
     }
     .inhuman_fieldset select {
         min-width: 10em;
     }
    </style>

<p>
    <label for="inhuman_meta[type]" class="inhuman_label" style="width:3em;">Type</label>
        <select name="inhuman_meta[type]" id="inhuman_meta[type]">
            <option value="">Article</option>
            <option value="screenshot" <?php selected($meta['type'], "screenshot"); ?>>Screenshot</option>
            <option value="plain" <?php selected($meta['type'], "plain"); ?>>Card only</option>
            <option value="background" <?php selected($meta['type'], "background"); ?>>Invisible card (content on background)</option>
        </select>
    </p>

    <fieldset class="inhuman_fieldset">
        <legend>Homepage Card</legend>

        <p>
            <input type="checkbox" name="inhuman_meta[featured]" id="inhuman_meta[featured]" <?php checked($meta['featured'], "on"); ?>>
            <label for="inhuman_meta[featured]">Featured in top section</label>
        </p>

        <p>
            <label for="inhuman_meta[sort]" class="inhuman_label">Sort order</label>
            <select name="inhuman_meta[sort]" id="inhuman_meta[sort]">
                <option value="">Don't Pin</option>
                <option value="1" <?php selected($meta['sort'], 1); ?>>1</option>
                <option value="2" <?php selected($meta['sort'], 2); ?>>2</option>
                <option value="3" <?php selected($meta['sort'], 3); ?>>3</option>
                <option value="4" <?php selected($meta['sort'], 4); ?>>4</option>
                <option value="5" <?php selected($meta['sort'], 5); ?>>5</option>
                <option value="6" <?php selected($meta['sort'], 6); ?>>6</option>
                <option value="7" <?php selected($meta['sort'], 7); ?>>7</option>
                <option value="8" <?php selected($meta['sort'], 8); ?>>8</option>
                <option value="9" <?php selected($meta['sort'], 9); ?>>9</option>
                <option value="10" <?php selected($meta['sort'], 10); ?>>10</option>
            </select>
        </p>

        <p>
            <label for="inhuman_meta[width]" class="inhuman_label">Width</label>
            <select name="inhuman_meta[width]" id="inhuman_meta[width]">
                <option value="">Standard</option>
                <option value="wide" <?php selected($meta['width'], 'wide'); ?>>Wide</option>
                <option value="wide2" <?php selected($meta['width'], 'wide2'); ?>>Extra-wide</option>
            </select>
        </p>

        <p>
            <label for="inhuman_meta[height]" class="inhuman_label">Height</label>
            <select name="inhuman_meta[height]" id="inhuman_meta[height]">
                <option value="">Standard</option>
                <option value="short" <?php selected($meta['height'], 'short'); ?>>Short</option>
                <option value="tall" <?php selected($meta['height'], 'tall'); ?>>Tall</option>
            </select>
        </p>
    </fieldset>

    <fieldset class="inhuman_fieldset">
        <legend>Screenshots</legend>
        <p>
            <label for="inhuman_meta[screenshot]" class="inhuman_label">Image URL</label>
            <input type="text" name="inhuman_meta[screenshot]" id="inhuman_meta[screenshot]"
                   value="<?php echo $meta['screenshot']; ?>">
        </p>
    </fieldset>

<?php }
}
add_action('add_meta_boxes', ['Inhuman_Meta', 'add']);
add_action('save_post', ['Inhuman_Meta', 'save']);

?>

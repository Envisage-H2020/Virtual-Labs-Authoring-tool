<?php

$sceneClass = new SceneClass();

class SceneClass{

    function __construct(){
        add_action('init', array($this, 'wpunity_scenes_construct')); //wpunity_scene
        add_action('init', array($this, 'wpunity_scenes_taxgame')); //wpunity_scene_pgame


        //register_activation_hook(__FILE__, array($this, 'activate'));


//        add_action('init', array($this, 'register_new_taxonomy_terms_scene'));
//        add_action("save_post", array($this, 'save_data_to_db_and_media'), 10, 3);
//        add_action('admin_footer', array($this, 'checktoradio'));
//        add_filter('get_sample_permalink', array($this, 'disable_permalink'));
//        add_action('edit_form_after_title', array($this, 'create_folder_scene'));
//        add_filter('geodir_custom_field_input_textarea', array($this,'scene_json_textarea_prolong'), 10, 1);
    }





    function wpunity_scenes_construct(){

        $labels = array(
            'name'               => _x( 'Scenes', 'post type general name'),
            'singular_name'      => _x( 'Scene', 'post type singular name'),
            'menu_name'          => _x( 'Scenes', 'admin menu'),
            'name_admin_bar'     => _x( 'Scene', 'add new on admin bar'),
            'add_new'            => _x( 'Add New', 'add new on menu'),
            'add_new_item'       => __( 'Add New Scene'),
            'new_item'           => __( 'New Scene'),
            'edit'               => __( 'Edit'),
            'edit_item'          => __( 'Edit Scene'),
            'view'               => __( 'View'),
            'view_item'          => __( 'View Scene'),
            'all_items'          => __( 'All Scenes'),
            'search_items'       => __( 'Search Scenes'),
            'parent_item_colon'  => __( 'Parent Scenes:'),
            'parent'             => __( 'Parent Scene'),
            'not_found'          => __( 'No Scenes found.'),
            'not_found_in_trash' => __( 'No Scenes found in Trash.')
        );

        $args = array(
            'labels'                => $labels,
            'description'           => 'Displays several Scenes of a Game',
            'public'                => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'show_in_nav_menus'     => false,
            'menu_position'     => 25,
            'menu_icon'         =>'dashicons-visibility',
            'taxonomies'        => array(),
            'supports'          => array('title','editor','thumbnail','custom-fields'),
            'hierarchical'      => false,
            'has_archive'       => false,
        );

        register_post_type('wpunity_scene', $args);
    }

    function wpunity_scenes_taxgame(){

        $labels = array(
            'name'              => _x( 'Scene Game', 'taxonomy general name'),
            'singular_name'     => _x( 'Scene Game', 'taxonomy singular name'),
            'menu_name'         => _x( 'Scene Games', 'admin menu'),
            'search_items'      => __( 'Search Scene Games'),
            'all_items'         => __( 'All Scene Games'),
            'parent_item'       => __( 'Parent Scene Game'),
            'parent_item_colon' => __( 'Parent Scene Game:'),
            'edit_item'         => __( 'Edit Scene Game'),
            'update_item'       => __( 'Update Scene Game'),
            'add_new_item'      => __( 'Add New Scene Game'),
            'new_item_name'     => __( 'New Scene Game')
        );

        $args = array(
            'description' => 'Game that the scene belongs',
            'labels'    => $labels,
            'public'    => false,
            'show_ui'   => true,
            'hierarchical' => true,
            'show_admin_column' => true
        );

        register_taxonomy('wpunity_scene_pgame', 'wpunity_scene', $args);

    }


    /*********************************************************************************************************************/



    /*
     *     This category is the Game which Scene belongs to.
     *
     */
    function register_new_taxonomy_terms_scene(){

        $args = array(
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'game',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'author_name'	   => '',
            'post_status'      => 'publish',
            'suppress_filters' => true
        );
        $posts_array = get_posts( $args );


        foreach ($posts_array as $p){
            $this->terms[] = array(
                'name' => $p->post_title,
                'slug' => $p->post_name,
                'description' => ''
            );
        }


        $this->taxonomy = 'scene_category';

        // now create the categories
        foreach ($this->terms as $term_key => $term) {

            if (get_term($term)->slug == 'uncategorized') {
                wp_insert_term(
                    $term['name'],
                    $this->taxonomy,
                    array(
                        'description' => $term['description'],
                        'slug' => $term['slug'],
                    )
                );
                unset($term);
            }
        }


    }











    function add_scene_metaboxes() {
        add_meta_box("scene_custom_fields_metabox", "Scene custom fields", array($this, "scene_custom_fields"), "scene", "normal", "default", null);
    }


    function scene_custom_fields($object)
    {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");

        ?>


        <div>
            <label for="scene-vr-editor" style="margin-right:30px">VR Web Editor</label>
            <div name="scene-vr-editor" style="margin-bottom:30px;">
	            <?php require( "vr_editor.php" );?>
            </div>
        </div>

        <div>
            <label for="scene-json-input" style="margin-right:30px; vertical-align: top">Scene json</label>
            <textarea name="scene-json-input" style="width:70%;height:800px;"
            ><?php echo get_post_meta($object->ID, "scene-json", true); ?></textarea>
        </div>

        <div>
            <label for="scene-latitude-input" style="margin-right:30px; vertical-align: top">Geolocation latitude</label>
            <input type="text" name="scene-latitude-input" style="width: 10ch;height:1em"
            value="<?php echo get_post_meta($object->ID, "scene-latitude", true); ?>"</input>
        </div>


        <div>
            <label for="scene-longitude-input" style="margin-right:30px; vertical-align: top">Geolocation longitude</label>
            <input type="text" name="scene-longitude-input" style="width: 10ch;height:1em"
                   value="<?php echo get_post_meta($object->ID, "scene-longitude", true); ?>"</input>
        </div>


        <?php

        // end of custom fields
    }

    function activate(){
//        $this->wpunity_scenes_construct();
//        $this->create_taxonomies_scene();
//        $this->register_new_taxonomy_terms_scene();
    }

    /**
     * Now Save everything to db
     *
     * @param $post_id
     * @param $post
     * @param $update
     * @return mixed
     */
    function save_data_to_db_and_media($post_id, $post, $update)
    {
        // Safety check for intruders
        if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
            return $post_id;

        // check permissions for current user
        if(!current_user_can("edit_post", $post_id))
            return $post_id;

        // check for autosave
        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;

        // Avoid changing other custom types
        if($post->post_type != 'scene' )
            return $post_id;

        // ============ Start of custom fields =============================================
        // $scene_category_slug = get_the_terms($post, 'scene_category')[0]->slug;
        $post_slug = $post->post_name;

        // --------------- Scene JSON -------------------
        $scene_json= "";

        if(isset($_POST["scene-json-input"]))
            $scene_json = $_POST["scene-json-input"];

        update_post_meta($post_id, "scene-json", $scene_json);

        //--------------- Scene Lat Long -------------------------
        $scene_lat= "";
        $scene_lng= "";

        if(isset($_POST["scene-latitude-input"]))
            $scene_lat = $_POST["scene-latitude-input"];

        if(isset($_POST["scene-longitude-input"]))
            $scene_lng = $_POST["scene-longitude-input"];

        update_post_meta($post_id, "scene-latitude", $scene_lat);
        update_post_meta($post_id, "scene-longitude", $scene_lng);
        // =========================================================
    }




    /**
     * Add "Edit in VR button" to post edit
     */
//    function vr_Scene_Edit(){
//
//        if (get_post_type()=='scene') {
//
//            $html  = '<div id="major-publishing-actions" style="overflow:hidden">';
//            $html .= '<div id="publishing-action">';
//
//            $html .= '<input accesskey="v" tabindex="5" value="VR Web Editor"
//							class="button-primary" id="editVR" name="editVR" onclick="window.open(\''.plugins_url().
//                                              '/WordpressUnity3DEditor/includes/vr_editor.php\',\'_blank\')">';
//            $html .= '</div>';
//            $html .= '</div>';
//            echo $html;
//        }
//
//    }



    /**
     * in cpt Scene allow only one selection in custom taxonomy scene_category, i.e. the Scene belongs to one Game only
     */
    function checktoradio(){
        echo '<script type="text/javascript">jQuery("#scene_categorychecklist-pop input, #scene_categorychecklist input, .scene_categorychecklist input").each(function(){this.type="radio"});</script>';
    }


    function disable_permalink(){
        return null;
    }


    function create_folder_scene(){

        global $post;

        // Generate a folder in media to store assets named as the permalink of the game
        if (get_post_type($post)=='scene') {
            $post_slug = $post->post_name;
            $subfolder_game = get_the_terms($post, 'scene_category')[0]->slug;

            // TODO: do not allow to continue saving and display a message if get_the_terms($post, 'scene_category')[0] has length 0
            //       i.e. not category is selected for this scene
            $media_subfolder_to_generate = $subfolder_game . '/' . $post_slug;
            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . "/" . $media_subfolder_to_generate;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755);
            }
        }

    }

}
?>
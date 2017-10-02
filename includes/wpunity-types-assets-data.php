<?php

//SIDEBAR of Asset3D with fetch-segmentation etc...

function loadTypesAssetsDataScripts() {
// load css/wpunity_backend.css
	wp_enqueue_style('wpunity_backend');

// load script from js_libs
	wp_enqueue_script( 'wpunity_content_interlinking_request');

// load script from js_libs
	wp_enqueue_script( 'wpunity_classification_request');

	wp_enqueue_script('wpunity_segmentation_request');

// Three js : for simple rendering
	wp_enqueue_script('wpunity_load_threejs');
	wp_enqueue_script('wpunity_load_objloader');
	wp_enqueue_script('wpunity_load_mtlloader');
	wp_enqueue_script('wpunity_load_orbitcontrols');


	// Some parameters to pass in the content_interlinking.js  ajax
	wp_localize_script('wpunity_content_interlinking_request', 'phpvars',
		array('lang' => 'en',
		      'externalSource' => 'Wikipedia',
		      'titles' => 'Scladina'  //'Albert%20Einstein'
		)
	);

// Some parameters to pass in the segmentation.js  ajax
	if( isset($_GET['post']) ){
		wp_localize_script('wpunity_segmentation_request', 'phpvars',
			array('path' => get_post_meta($_GET['post'], 'wpunity_asset3d_pathData', true).'/',
			      'obj'  => get_post_meta($_GET['post'], 'wpunity_asset3d_obj', true)
			)
		);

	}



// Some parameters to pass in the classification.js  ajax
	wp_localize_script('wpunity_classification_request', 'phpvars',
		array('path' => get_post_meta($post->ID, 'wpunity_asset3d_pathData', true).'/',
		      'obj' => get_post_meta($post->ID, 'wpunity_asset3d_obj', true)
		)
	);
}
add_action('wp_enqueue_scripts', 'loadTypesAssetsDataScripts' );



add_action('add_meta_boxes','wpunity_assets_create_right_metaboxes');

function wpunity_assets_create_right_metaboxes() {
	add_meta_box( 'autofnc-wpunity_asset3d_fetch_description','Fetch description','wpunity_assets_fetch_description_box_content', 'wpunity_asset3d', 'side' , 'low');
	add_meta_box( 'autofnc-wpunity_asset3d_fetch_image','Fetch image','wpunity_assets_fetch_image_box_content', 'wpunity_asset3d', 'side' , 'low');
	add_meta_box( 'autofnc-wpunity_asset3d_fetch_video','Fetch video','wpunity_assets_fetch_video_box_content', 'wpunity_asset3d', 'side' , 'low');
	add_meta_box( 'autofnc-wpunity_asset3d_segment_obj','Segment obj','wpunity_assets_segment_obj_box_content', 'wpunity_asset3d', 'side' , 'low');
	add_meta_box( 'autofnc-wpunity_asset3d_classify_obj','Classify obj','wpunity_assets_classify_obj_box_content', 'wpunity_asset3d', 'side' , 'low');
}

function wpunity_assets_fetch_description_box_content($post){



	echo '<div id="wpunity_fetchDescription_bt" class="wpunity_fetchContentButton" onclick="wpunity_fetchDescriptionAjax()">Fetch Description</div>';
	?>

    <br /><br />

    Source:<br />
    <select name="fetch_source" id="fetch_source">
        <option value="Wikipedia">Wikipedia</option>
        <option value="Europeana">Europeana</option>
    </select>

    <br />
    <br />

    Language<br />
    <select name="fetch_lang" id="fetch_lang">
        <option value="en">English</option>
        <option value="el">Greek</option>
        <option value="fr">French</option>
        <option value="de">German</option>
    </select>

    <br />
    <br />
    Terms to search:<input type="text" size="30" name="wpunity_titles_search" id="wpunity_titles_search" value="<?php echo $post->post_title?>">

    <br />
    <br />

    Full text:<input type="checkbox" name="wpunity_fulltext_chkbox" id="wpunity_fulltext_chkbox" value="">


	<?php
}

function wpunity_assets_fetch_image_box_content($post){

	echo '<div id="wpunity_fetchImage_bt" class="wpunity_fetchContentButton" onclick="wpunity_fetchImageAjax()">Fetch Image</div>';
	?>

    <br /><br />

    Source:<br />
    <select name="fetch_source_image" id="fetch_source_image">
        <option value="Wikipedia">Wikipedia</option>
        <option value="Europeana">Europeana</option>
    </select>

    <br />
    <br />

    Language<br />
    <select name="fetch_lang_image" id="fetch_lang_image">
        <option value="en">English</option>
        <option value="el">Greek</option>
        <option value="fr">French</option>
        <option value="de">German</option>
    </select>

    <br />
    <br />
    Terms to search:<input type="text" size="30" name="wpunity_titles_image_search_image" id="wpunity_titles_image_search_image" value="<?php echo $post->post_title?>">

    <br />
    <br />



    <div id="image_find_results">
		<?php

		echo '<div id="display_img_res" class="imageresbin" style="display:none">';
		for ($i=0;$i<10;$i++) {
			echo '<img id = "image_res_'.$i.'" class="image_fetch_img" />';
			echo '<div id = "image_res_'.$i.'_url" class="image_fetch_div_url" style="margin-bottom:5px"></div >';
			echo '<a href="" id = "image_res_'.$i.'_title" class="img_res_title_f" target = "_blank" style="margin-bottom:10px"></a >';
		}

		echo '</div>';
		?>
    </div>


	<?php
}

function wpunity_assets_fetch_video_box_content($post){

	echo '<div id="wpunity_fetchVideo_bt" class="wpunity_fetchContentButton" onclick="wpunity_fetchVideoAjax()">Fetch Video</div>';
	?>

    <br /><br />

    Source:<br />
    <select name="fetch_source_video" id="fetch_source_video">
        <option value="Wikipedia">Wikipedia</option>
        <option value="Europeana">Europeana</option>
    </select>

    <br />
    <br />

    Language<br />
    <select name="fetch_lang_video" id="fetch_lang_video">
        <option value="en">English</option>
        <option value="el">Greek</option>
        <option value="fr">French</option>
        <option value="de">German</option>
    </select>

    <br />
    <br />
    Terms to search:<input type="text" size="30" name="wpunity_titles_video_search_video" id="wpunity_titles_video_search_video" value="<?php echo $post->post_title?>">
    Wikipedia example:<br /> "Sarmientosaurus 3D skull"
    <br />
    <br />

    <div id="video_find_results">

        <video id="videoplayer1" width="240" height="160" autoplay controls>
            <source id="video_res_1" src="" type="video/mp4">
            <source id="video_res_1" src="" type="video/ogg">
            <source id="video_res_1" src="" type="video/ogv">
        </video>
        <div id="video_res_1_url" class="video_fetch_div_url"></div><br />
        <div id="video_res_1_title" class="video_res_title_f"></div><br />

    </div>

	<?php
}

function wpunity_assets_segment_obj_box_content($post){

	?>

    <div id="wpunity_segmentButton" class="wpunity_fetchContentButton"
         onclick="wpunity_segmentObjAjax(document.getElementById('wpunity_titles_segment_obj_iter').value,
                                         document.getElementById('wpunity_titles_segment_obj_min_dist').value,
                                         document.getElementById('wpunity_titles_segment_obj_max_dist').value,
                                         document.getElementById('wpunity_titles_segment_obj_min_points').value,
                                         document.getElementById('wpunity_titles_segment_obj_max_points').value
                                            )">Segment obj</div>;

    <br />
    Parameters<br />
    <table>
        <tbody>
        <tr><td>Algorithm iterations</td><td><input type="text" size="5" name="wpunity_titles_segment_obj_iter" id="wpunity_titles_segment_obj_iter" value="100"></td></tr>
        <tr><td>Min distance</td><td><input type="text" size="5" name="wpunity_titles_segment_obj_min_dist" id="wpunity_titles_segment_obj_min_dist" value="0.01"></td></tr>
        <tr><td>Max distance</td><td><input type="text" size="5" name="wpunity_titles_segment_obj_max_dist" id="wpunity_titles_segment_obj_max_dist" value="0.2"></td></tr>
        <tr><td>Min points</td><td><input type="text" size="5" name="wpunity_titles_segment_obj_min_points" id="wpunity_titles_segment_obj_min_points" value="100"></td></tr>
        <tr><td>Max points</td><td><input type="text" size="5" name="wpunity_titles_segment_obj_max_points" id="wpunity_titles_segment_obj_max_points" value="25000"></td></tr>
        </tbody>
    </table>

    <br />
    <div id="wpunity-segmentation-report" name="wpunity-segmentation-report">Status</div><br />
    <div id="wpunity-segmentation-status" name="wpunity-segmentation-status">Report</div><br />

    <br />
    Results<br />
    <div id="wpunity-segmentation-results" name="wpunity-segmentation-results">
        <a href="" id="wpunity-segmentation-res1"></a>
        <a href="" id="wpunity-segmentation-res2"></a>
        <a href="" id="wpunity-segmentation-res3"></a>
        <a href="" id="wpunity-segmentation-res4"></a>
        <a href="" id="wpunity-segmentation-res5"></a>
        <a href="" id="wpunity-segmentation-res6"></a>

    </div>

    <br />
    <div id="wpunity-segmentation-log" name="wpunity-segmentation-log">Log file</div>

	<?php
}

function wpunity_assets_classify_obj_box_content($post){

	echo '<div id="wpunity_classifyObj_bt" class="wpunity_fetchContentButton" 
                                onclick="wpunity_classifyObjAjax()">Classify obj</div>';
	?>

    <br />
    Results<br />
    <table>
        <tbody>
        <tr>
            <td>#</td>
            <td>Tag</td>
            <td>Probability</td>
        </tr>
        <tr>
            <td>1</td>
            <td><input type="text" size="5" name="wpunity_tag1_classification_obj"
                       id="wpunity_tag1_classification_obj" value=""></td>
            <td><input type="text" size="5" name="wpunity_prob1_classification_obj"
                       id="wpunity_prob1_classification_obj" value=""></td>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td><input type="text" size="5" name="wpunity_tag2_classification_obj"
                       id="wpunity_tag2_classification_obj" value=""></td>
            <td><input type="text" size="5" name="wpunity_prob2_classification_obj"
                       id="wpunity_prob2_classification_obj" value=""></td>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td><input type="text" size="5" name="wpunity_tag3_classification_obj"
                       id="wpunity_tag3_classification_obj" value=""></td>
            <td><input type="text" size="5" name="wpunity_prob3_classification_obj"
                       id="wpunity_prob3_classification_obj" value=""></td>
            </td>
        </tr>
        </tbody>
    </table>

    <br />
    <div id="wpunity-classification-report" name="wpunity-classification-report">Status</div><br />
    <div id="wpunity-classification-status" name="wpunity-classification-status">Report</div><br />
    <div id="wpunity-segmentation-log" name="wpunity-segmentation-log">Log file</div>

	<?php
}

//==========================================================================================================================================

// Create metabox with Custom Fields for Asset3D ($wpunity_databox1)
$wpunity_prefix = 'wpunity_asset3d_';

//All information about our meta box
$wpunity_databox1 = array(
	'id' => 'wpunity-assets-databox',
	'page' => 'wpunity_asset3d',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'MTL File',
			'desc' => 'MTL File',
			'id' => $wpunity_prefix . 'mtl',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Obj File',
			'desc' => 'Obj File',
			'id' => $wpunity_prefix . 'obj',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Diffusion Image',
			'desc' => 'Diffusion Image',
			'id' => $wpunity_prefix . 'diffimage',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Screenshot Image',
			'desc' => 'Screenshot Image',
			'id' => $wpunity_prefix . 'screenimage',
			'type' => 'text',
			'std' => ''
		),array(
			'name' => 'Next Scene (Only for Doors)',
			'desc' => 'Next Scene',
			'id' => $wpunity_prefix . 'next_scene',
			'type' => 'text',
			'std' => ''
		),array(
			'name' => 'Video',
			'desc' => 'Video',
			'id' => $wpunity_prefix . 'video',
			'type' => 'text',
			'std' => ''
		),array(
			'name' => 'Image 1',
			'desc' => 'Image 1',
			'id' => $wpunity_prefix . 'image1',
			'type' => 'text',
			'std' => ''
		),
	)
);

//==========================================================================================================================================

// Add and Show the metabox with Custom Field for Game ($wpunity_databox1)
function wpunity_assets_databox_add() {
	global $wpunity_databox1;
	add_meta_box('wpunity-assets-infobox', 'Description Tips for Image-Text', 'wpunity_assets_infobox_show', 'wpunity_asset3d','normal','high' );
	add_meta_box($wpunity_databox1['id'], 'Asset Data', 'wpunity_assets_databox_show', $wpunity_databox1['page'], $wpunity_databox1['context'], $wpunity_databox1['priority']);
}

add_action('admin_menu', 'wpunity_assets_databox_add');

function wpunity_assets_infobox_show(){
	?>
    <style>#wpunity-assets-infobox{display:none;}</style>

    &lt;b&gt;&lt;size=40&gt;MyTitle&lt;/size&gt;&lt;/b&gt; <br/>

    &lt;size=32&gt;&lt;color=green>My description goes here.&lt;/color&gt;&lt;/size&gt; <br/><br/>

    Supported tags<br/>

    &lt;b&gt;Renders the text in boldface.&lt;/b&gt;<br/>
    &lt;i&gt;Renders the text in italics.&lt;/i&gt;<br/>
    &lt;size=20&gt;Sets the size of the text according to the parameter value, given in pixels.&lt;/size&gt;<br/>
    &lt;color=blue&gt;Sets the color of the text according to the parameter value.&lt;/color&gt;<br/>

	<?php
}

function wpunity_assets_databox_show(){
	global $wpunity_databox1, $post;

	$post_title = $post->post_title;
	if($post->post_status == 'publish'){$hideshow = 'none';}else{$hideshow = 'block';}
	?>
    <div id="wpunity_assets_box_wrapper" style="display:<?php echo $hideshow; ?>;">
        <span class="dashicons dashicons-lock">You must create the Asset in order to fill data</span>
    </div>
    <input type="hidden" name="wpunity_assets_databox_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
    <table class="form-table" id="wpunity-custom-fields-table">
        <tbody>
        <tr><th style="width:20%">Asset 3D Preview</label></th>
			<?php
			$curr_path =  wp_upload_dir()['baseurl'].'/'.get_post_meta($post->ID, 'wpunity_asset3d_pathData', true) . '/Models/';
			$mtl_obj = get_post_meta($post->ID, 'wpunity_asset3d_mtl', true);

			if (wp_get_attachment_url( $mtl_obj ))
				$textmtl = file_get_contents(wp_get_attachment_url( $mtl_obj ));
			else
				$textmtl = '';

			//    '    characters because they cause conflict
			$textmtl = str_replace("'", "", $textmtl);
			$textmtl = str_replace("\'", "", $textmtl);

			$obj_id = get_post_meta($post->ID, 'wpunity_asset3d_obj', true);
			$url_obj = wp_get_attachment_url( $obj_id );
			?>
            <td>
                <div name="vr-preview-progress-content" id="vr-preview-progress-content"
                     style="position: absolute; top:10%; background: #af0; color: #fff; margin-left: auto; margin-right: auto;  width:100px; left:50%; text-align:center; z-index:1">
                    0%
                </div>

                <div name="vr-preview" id="vr-preview" style="position:relative; width:95%; border: 1px solid #aaa; margin-left:5px">

					<?php
					if ($curr_path != "" && $textmtl != "" && $url_obj != "") {

						wp_enqueue_script("wu_3d_view");
						?>

                        <script>
                            jQuery('document').ready(function(){
                                var view3d_back = new wu_3d_view(
                                    "after",
                                    "<?php echo $curr_path;?>",
									<?php echo json_encode($textmtl);?>,
                                    "<?php echo $url_obj;?>",
                                    "-1",
                                    "<?php echo $post_title;?>",
                                    "vr-preview" );
                            });
                        </script>

						<?php

					}else {
						echo "Rendering is not possible because:<br />";
						if ($curr_path == ""){echo "- Current path is not defined<br />";}
						if ($textmtl == ""){echo "- mtl is not defined<br />";}
						if ($url_obj == ""){echo "- obj url is not defined<br />";}
					}
					?>
                </div>
            </td>
        </tr>
		<?php
		//Hide-Show custom fields purpose
		$categoryAsset = wp_get_post_terms($post->ID, 'wpunity_asset3d_cat');
		$categoryAssetSlug = $categoryAsset[0]->name;
		$doorhideshow = 'none';$mediahideshow = 'none';
		if ($categoryAssetSlug == 'Doors') {$doorhideshow = 'block';$mediahideshow = 'none';}
		if ($categoryAssetSlug != 'Doors') {$doorhideshow = 'none';$mediahideshow = 'block';}

		foreach ($wpunity_databox1['fields'] as $field) {
			if ($field['id']=='wpunity_asset3d_mtl'){
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php $meta_mtl_id = get_post_meta($post->ID, $field['id'], true); ?>

                        <input type="text" name="<?php echo esc_attr($field['id']); ?>"
                               id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($meta_mtl_id ? $meta_mtl_id : $field['std']); ?>" size="30" style="width:65%"/>

                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>"/>


                        <br /><br />
                        Pathfile: <?php echo wp_get_attachment_url($meta_mtl_id); ?><br />
                        Preview mtl:<br />
                        <textarea id="wpunity_asset3d_mtl_preview" readonly style="width:100%;height:200px;"><?php

							if(!$meta_mtl_id){
								echo "mtl is not defined";
							}else{
								readfile(wp_get_attachment_url($meta_mtl_id));
							}
							?>
                            </textarea>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_obj') {

				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php
						$valMaxUpload = intval(ini_get('upload_max_filesize'));
						if ($valMaxUpload < 100){
							echo "Files bigger than ".$valMaxUpload. " MB can not be uploaded <br />";
							echo "Add to .htaccess the following two lines<br/>";
							echo "php_value upload_max_filesize 256M <br />";
							echo "php_value post_max_size 512M";
						}
						$meta_obj_id = get_post_meta($post->ID, $field['id'], true); ?>

                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>"
                               value="<?php echo esc_attr($meta_obj_id ? $meta_obj_id : $field['std']); ?>" size="30" style="width:65%"/>

                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>"/>

                        <br /><br />
                        Pathfile: <?php echo wp_get_attachment_url($meta_obj_id); ?><br />
                        Preview obj:<br />
                        <textarea id="wpunity_asset3d_obj_preview" readonly style="width:100%;height:200px;"><?php
							if(!$meta_obj_id){
								echo "obj is not defined";
							}else{
								echo "obj text is too big to state here.";
								//readfile(wp_get_attachment_url($meta_obj_id), "100");
							}
							?>
                            </textarea>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_diffimage') {
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php $meta_diff_id = get_post_meta($post->ID, $field['id'], true); ?>
                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>"
                               value="<?php
						       echo esc_attr($meta_diff_id ? $meta_diff_id : $field['std']);
						       ?>" size="30" style="width:65%"/>

                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>"/>
                        <br />
                        Pathfile: <?php echo wp_get_attachment_url($meta_diff_id); ?><br />
                        <img id="wpunity_asset3d_diffimage_preview" style="width:50%;height:auto" src="<?php echo wp_get_attachment_url($meta_diff_id); ?>"/>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_screenimage') {
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php $meta_scr_id = get_post_meta($post->ID, $field['id'], true); ?>

                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>"
                               value="<?php echo esc_attr($meta_scr_id ? $meta_scr_id : $field['std']); ?>" size="30" style="width:65%"/>

                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>"/>
                        <br />
                        Pathfile: <?php echo wp_get_attachment_url($meta_scr_id); ?><br />
                        <img id="wpunity_asset3d_screenimage_preview" style="width:50%;height:auto" src="<?php echo wp_get_attachment_url($meta_scr_id); ?>"/>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_next_scene') {
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td id="wpunity_asset3d_next_scene_field" style="display:<?php echo $doorhideshow; ?>;margin-bottom:0;">
						<?php $meta = get_post_meta($post->ID, $field['id'], true); ?>
                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($meta ? $meta : $field['std']); ?>" size="30" style="width:65%"/>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_image1') {
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php $meta_image1_id = get_post_meta($post->ID, $field['id'], true); ?>
                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($meta_image1_id ? $meta_image1_id : $field['std']); ?>" size="30" style="width:65%;float:left;display:<?php echo $mediahideshow; ?>;"/>
                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>" style="display:<?php echo $mediahideshow; ?>;" />

                        Pathfile: <?php echo wp_get_attachment_url($meta_image1_id); ?><br />
                        <img id="wpunity_asset3d_image1_preview" style="width:50%;height:auto;display:<?php echo $mediahideshow; ?>;"
                             src="<?php echo wp_get_attachment_url($meta_image1_id); ?>"/>
                    </td>
                </tr>
				<?php
			}elseif ($field['id'] == 'wpunity_asset3d_video') {
				?>
                <tr>
                    <th style="width:20%"><label for="<?php echo esc_attr($field['id']); ?>"> <?php echo esc_html($field['name']); ?> </label></th>
                    <td>
						<?php $meta = get_post_meta($post->ID, $field['id'], true); ?>
                        <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($meta ? $meta : $field['std']); ?>" size="30" style="width:65%;float:left;display:<?php echo $mediahideshow; ?>;"/>
                        <input id="<?php echo esc_attr($field['id']); ?>_btn" type="button" value="Upload <?php echo esc_html($field['name']); ?>"  style="display:<?php echo $mediahideshow; ?>;" />
						<?php //TODO preview of the video ?>
                    </td>
                </tr>
				<?php
			}
		}
		?>
        </tbody>
    </table>

    <script>
        function wpunity_hidecfields_asset3d() {
            var e = document.getElementById("wpunity-select-asset3d-cat-dropdown");
            var value = e.options[e.selectedIndex].value;
            var text = e.options[e.selectedIndex].text;

            if(text == 'Doors'){
                //SHOW Next Scene Custom field - Hide others
                document.getElementById('wpunity_asset3d_next_scene_field').style.display = 'block';
                document.getElementById('wpunity_asset3d_image1').style.display = 'none';
                document.getElementById('wpunity_asset3d_image1_btn').style.display = 'none';
                document.getElementById('wpunity_asset3d_image1_preview').style.display = 'none';
                document.getElementById('wpunity_asset3d_video').style.display = 'none';
                document.getElementById('wpunity_asset3d_video_btn').style.display = 'none';
                document.getElementById('wpunity-assets-infobox').style.display = 'none';
            }else{
                var link = document.getElementById('wpunity_asset3d_next_scene_field');
                link.style.display = 'none';
                if(text == 'Points of Interest (Video)'){
                    document.getElementById('wpunity_asset3d_image1').style.display = 'none';
                    document.getElementById('wpunity_asset3d_image1_btn').style.display = 'none';
                    document.getElementById('wpunity_asset3d_image1_preview').style.display = 'none';
                    document.getElementById('wpunity_asset3d_video').style.display = 'block';
                    document.getElementById('wpunity_asset3d_video_btn').style.display = 'block';
                    document.getElementById('wpunity-assets-infobox').style.display = 'none';
                }else if(text == 'Points of Interest (Image-Text)'){
                    document.getElementById('wpunity_asset3d_image1').style.display = 'block';
                    document.getElementById('wpunity_asset3d_image1_btn').style.display = 'block';
                    document.getElementById('wpunity_asset3d_image1_preview').style.display = 'block';
                    document.getElementById('wpunity_asset3d_video').style.display = 'none';
                    document.getElementById('wpunity_asset3d_video_btn').style.display = 'none';
                    document.getElementById('wpunity-assets-infobox').style.display = 'block';
                }else if(text == 'Points of Interest'){
                    document.getElementById('wpunity_asset3d_image1').style.display = 'block';
                    document.getElementById('wpunity_asset3d_image1_btn').style.display = 'block';
                    document.getElementById('wpunity_asset3d_image1_preview').style.display = 'block';
                    document.getElementById('wpunity_asset3d_video').style.display = 'block';
                    document.getElementById('wpunity_asset3d_video_btn').style.display = 'block';
                    document.getElementById('wpunity-assets-infobox').style.display = 'none';
                }else{
                    document.getElementById('wpunity_asset3d_image1').style.display = 'none';
                    document.getElementById('wpunity_asset3d_image1_btn').style.display = 'none';
                    document.getElementById('wpunity_asset3d_image1_preview').style.display = 'none';
                    document.getElementById('wpunity_asset3d_video').style.display = 'none';
                    document.getElementById('wpunity_asset3d_video_btn').style.display = 'none';
                    document.getElementById('wpunity-assets-infobox').style.display = 'none';
                }
            }
        }

        jQuery(document).ready(function ($) {

            // Uploading files
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
            var set_to_post_id = <?php echo $post->ID; ?>; // Set this

            jQuery('#wpunity_asset3d_mtl_btn').on('click', function( event ){

                event.preventDefault();

                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select MTL file to upload',
                    button: {
                        text: 'Use this file',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_mtl').val(attachment.id);
                    //jQuery('#wpunity_asset3d_mtl_preview').

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });

            jQuery('#wpunity_asset3d_obj_btn').on('click', function( event ){

                event.preventDefault();

                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select OBJ file to upload',
                    button: {
                        text: 'Use this file',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_obj').val(attachment.id);
                    //jQuery('#wpunity_asset3d_mtl_preview').

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });

            jQuery('#wpunity_asset3d_diffimage_btn').on('click', function( event ){

                event.preventDefault();

                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select photo to upload',
                    button: {
                        text: 'Use this photo',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_diffimage').val(attachment.id);
                    jQuery('#wpunity_asset3d_diffimage_preview').attr( 'src', attachment.url );

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });

            jQuery('#wpunity_asset3d_screenimage_btn').on('click', function( event ){

                event.preventDefault();

                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select photo to upload',
                    button: {
                        text: 'Use this photo',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_screenimage').val(attachment.id);
                    jQuery('#wpunity_asset3d_screenimage_preview').attr( 'src', attachment.url );

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });

            jQuery('#wpunity_asset3d_image1_btn').on('click', function( event ){

                event.preventDefault();

                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select photo to upload',
                    button: {
                        text: 'Use this photo',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_image1').val(attachment.id);
                    jQuery('#wpunity_asset3d_image1_preview').attr( 'src', attachment.url );

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });

            jQuery('#wpunity_asset3d_video_btn').on('click', function( event ){

                event.preventDefault();

                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select video to upload',
                    button: {
                        text: 'Use this video',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function(html) {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    jQuery('#wpunity_asset3d_video').val(attachment.id);
                    //jQuery('#wpunity_asset3d_image3_preview').attr( 'src', attachment.url );

                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });

                // Finally, open the modal
                file_frame.open();
            });


            // Restore the main ID when the add media button is pressed
            jQuery( 'a.add_media' ).on( 'click', function() {
                wp.media.model.settings.post.id = wp_media_post_id;
            });


        });
    </script>
	<?php
}

//==========================================================================================================================================

// Save data from this metabox with Custom Field for Asset3D ($wpunity_databox)
function wpunity_assets_databox_save($post_id) {
	global $wpunity_databox1;
	// verify nonce
	if (!wp_verify_nonce($_POST['wpunity_assets_databox_nonce'], basename(__FILE__))) {
		return $post_id;
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	foreach ($wpunity_databox1['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		update_post_meta($post_id, $field['id'], $new);
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

// Save data from infobox
add_action('save_post', 'wpunity_assets_databox_save');

//==========================================================================================================================================

// AJAXES for content interlinking
add_action( 'wp_ajax_wpunity_fetch_description_action', 'wpunity_fetch_description_action_callback' );
add_action( 'wp_ajax_wpunity_fetch_image_action', 'wpunity_fetch_image_action_callback' );
add_action( 'wp_ajax_wpunity_fetch_video_action', 'wpunity_fetch_video_action_callback' );

// AJAXES for semantics
add_action( 'wp_ajax_wpunity_segment_obj_action', 'wpunity_segment_obj_action_callback' );
add_action( 'wp_ajax_wpunity_monitor_segment_obj_action', 'wpunity_monitor_segment_obj_action_callback' );
add_action( 'wp_ajax_wpunity_enlist_splitted_objs_action', 'wpunity_enlist_splitted_objs_action_callback' );

add_action( 'wp_ajax_wpunity_classify_obj_action', 'wpunity_classify_obj_action_callback' );

// AJAX for delete asset
add_action('wp_ajax_wpunity_delete_asset_action', 'wpunity_delete_asset3d_frontend_callback');


//==========================================================================================================================================

?>
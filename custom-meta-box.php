<?php
add_action( 'add_meta_boxes', 'cpt_add_custom_box' );
function cpt_add_custom_box() 
{    
    add_meta_box( 
        'cpt_meta_box',
        'Custom Meta Box',
        'cpt_meta_box_callback',
        'cpt_post_type' ,
        'normal',
        "high"
    );
}

add_action( 'save_post', 'cpt_save_postdata' );
function cpt_save_postdata($post_id) 
{   
    // verify if this is an auto save routine
    // If it is our form has not been submitted, so we dont want to do anything
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // verify this came from the our screen and with proper authorization
    // because save_post can be triggered at other times
    $_POST['cpt_noncename'] = isset($_POST['cpt_noncename']) ? $_POST['cpt_noncename'] : '';
    if(!wp_verify_nonce( $_POST['cpt_noncename'], plugin_basename( __FILE__ ))) return;

    // Check permissions
    if(isset($_POST['post_type']))
    {
        if('page' == $_POST['post_type']) 
        {
            if(! current_user_can('edit_page', $post_id)) return;
        }
        else
        {
            if(! current_user_can('edit_post', $post_id)) return;
        }   
    }
    
    $post_meta = $_POST['cpt_meta_field'];

    //trim data
    foreach ( $post_meta as $key => $value) 
    {
        $postmeta[$key]= trim($value);
        $postmeta[$key]= esc_attr($value);
    }

    // update_post_meta($post_id,'cpt_meta_box',$post_meta);
    update_post_meta($post_id,'cpt_meta_box',$post_meta);
}

/** 
 * Prints the box content 
 */
function cpt_meta_box_callback( $post ) 
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'cpt_noncename' );

    $postmeta = get_post_meta($post->ID, 'cpt_meta_box',true);

    if (!isset($postmeta["input_text"])) 
        $postmeta["input_text"] = 'input text';

    if (!isset($postmeta["input_select"])) 
        $postmeta["input_select"] = 'option 1';

    if (!isset($postmeta["input_checkbox_1"])) 
        $postmeta["input_checkbox_1"] = '';

    if (!isset($postmeta["input_checkbox_2"])) 
        $postmeta["input_checkbox_2"] = '';

    if (!isset($postmeta["input_checkbox_3"])) 
        $postmeta["input_checkbox_3"] = '';

    if (!isset($postmeta["input_radio"])) 
        $postmeta["input_radio"] = 'radio 1';

    if (!isset($postmeta["input_textarea"])) 
        $postmeta["input_textarea"] = 'input textarea';

    ?>

    <style type="text/css">
        input[type='text'], select, textarea {
            width: 300px;
        }

        textarea {
            height: 100px;
        }
    </style>

    <table class="form-table">
        <tr>
            <th>Text</th>
            <td><input type="text" name="cpt_meta_field[input_text]" value="<?php echo $postmeta['input_text'] ?>"></td>
        </tr>
        <tr>
            <th>Select</th>
            <td>
                <?php 
                    $arr_data = array(                                  
                        '0' => array(
                            'value' =>  'option 1',
                            'label' =>  '1st option' 
                        ),
                        '1' => array(
                            'value' =>  'option 2',
                            'label' =>  '2nd option'
                        ),
                        '2' => array(
                            'value' =>  'option 3',
                            'label' =>  '3rd option'
                        )
                    );

                    form_lib_print_select($arr_data,"cpt_meta_field[input_select]",$postmeta['input_select']);
                ?>
            </td>
        </tr>
        <tr>
            <th>Checkbox</th>
            <td>
                <?php 
                    $arr_data = array(                                  
                        '0' => array(
                            'name' =>   'cpt_meta_field[input_checkbox_1]',
                            'value' =>  'checkbox 1',
                            'label' =>  '1st checkbox',
                            'saved_value' => $postmeta['input_checkbox_1']
                        ),
                        '1' => array(
                            'name' =>   'cpt_meta_field[input_checkbox_2]',
                            'value' =>  'checkbox 2',
                            'label' =>  '2nd checkbox',
                            'saved_value' => $postmeta['input_checkbox_2']
                        ),
                        '2' => array(
                            'name' =>   'cpt_meta_field[input_checkbox_3]',
                            'value' =>  'checkbox 3',
                            'label' =>  '3rd checkbox',
                            'saved_value' => $postmeta['input_checkbox_3']
                        )
                    );

                    form_lib_print_checkbox($arr_data);
                ?>
            </td>
        </tr>
        <tr>
            <th>Radio</th>
            <td>
                <?php 
                    $arr_data = array(                                  
                        '0' => array(
                            'value' =>  'radio 1',
                            'label' =>  '1st radio' 
                        ),
                        '1' => array(
                            'value' =>  'radio 2',
                            'label' =>  '2nd radio'
                        ),
                        '2' => array(
                            'value' =>  'radio 3',
                            'label' =>  '3rd radio'
                        )
                    );

                    form_lib_print_radio($arr_data,"cpt_meta_field[input_radio]",$postmeta['input_radio']);
                ?>
            </td>
        </tr>
        <tr>
            <th>Textarea</th>
            <td><textarea name="cpt_meta_field[input_textarea]"><?php echo $postmeta['input_textarea'] ?></textarea></td>
        </tr>
    </table>
<?php 

}
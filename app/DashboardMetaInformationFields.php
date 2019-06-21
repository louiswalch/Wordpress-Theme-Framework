<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_5b3cf7089ef12',
    'title' => 'Meta Information (2)',
    'fields' => array(
        array(
            'key' => 'field_5b3cfd02b380f',
            'label' => '',
            'name' => 'test',
            'type' => 'checkbox',
            'instructions' => 'Your site will automatically populate the values displayed when a page is shared on social media platforms or listed in Google. You can override them with custom values if you like.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'yes' => 'Customize',
            ),
            'allow_custom' => 0,
            'save_custom' => 0,
            'default_value' => array(
            ),
            'layout' => 'horizontal',
            'toggle' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_5b3cf78448a33',
            'label' => 'Description',
            'name' => 'share_description',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5b3cfd02b380f',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '(Generate Automatically)',
            'maxlength' => '',
            'rows' => 4,
            'new_lines' => '',
        ),
        array(
            'key' => 'field_5b3cf78e48a34',
            'label' => 'Image',
            'name' => 'share_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5b3cfd02b380f',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'url',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array(
            'key' => 'field_5b3cf7a448a35',
            'label' => 'Include In Google',
            'name' => 'allow_indexing',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5b3cfd02b380f',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 1,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        array(
            'key' => 'field_5b3cf76f48a32',
            'label' => 'Title',
            'name' => 'share_title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5b3cfd02b380f',
                        'operator' => '==',
                        'value' => 'yes',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '(Generate Automatically)',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'page',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'product',
            ),
        ),
    ),
    'menu_order' => 100,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

endif;
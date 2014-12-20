<?php


function buddyforms_woocommerce_admin_settings_sidebar_metabox($form, $selected_form_slug){

    $form->addElement(new Element_HTML('
		<div class="accordion-group postbox">
			<div class="accordion-heading"><p class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_'.$selected_form_slug.'" href="#accordion_'.$selected_form_slug.'_woocommerce_fields">WooCommerce Fields</p></div>
		    <div id="accordion_'.$selected_form_slug.'_woocommerce_fields" class="accordion-body collapse">
				<div class="accordion-inner">'));

    $form->addElement(new Element_HTML('<p><b>Product General Data</b></p>'));

        $form->addElement(new Element_HTML('<p><a href="General/'.$selected_form_slug.'/unique" class="action">General</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Inventory</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Inventory/'.$selected_form_slug.'/unique" class="action">Inventory</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Shipping</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Shipping/'.$selected_form_slug.'/unique" class="action">Shipping</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Linked Products</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Linked-Products/'.$selected_form_slug.'/unique" class="action">Linked Products</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Attributes</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Attributes/'.$selected_form_slug.'/unique" class="action">Attributes</a></p>'));

    $form->addElement(new Element_HTML('<p><b>Product Gallery</b></p>'));
        $form->addElement(new Element_HTML('<p><a href="Product-Gallery/'.$selected_form_slug.'/unique" class="action">Product Gallery</a></p>'));

    $form->addElement(new Element_HTML('
				</div>
			</div>
		</div>'));

    return $form;
}
add_filter('buddyforms_admin_settings_sidebar_metabox','buddyforms_woocommerce_admin_settings_sidebar_metabox',1,2);


function buddyforms_woocommerce_create_new_form_builder_form_element($form_fields, $form_slug, $field_type, $field_id){
    global $field_position;
    $buddyforms_options = get_option('buddyforms_options');

    switch ($field_type) {

        case 'General':

            unset($form_fields);

            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'General');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_general');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            $product_type_hidden = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden']))
                $product_type_hidden = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden'];
            $form_fields['full']['product_type_hidden']		= new Element_Checkbox('' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_type_hidden]",array('hidden' => '<b>' .__('Make the Product Type a Hidden Field', 'buddyforms') . '</b>'),array('id' => 'product_type_hidden'.$form_slug.'_'.$field_id, 'class' => 'product_type_hidden' , 'value' => $product_type_hidden));

            $form_fields['full']['hr1'] = new Element_HTML('<hr>');

            $product_type_hidden_checked = isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_hidden']) ? '' : 'style="display: none;"';

            $form_fields['full']['product_type_default_div_start'] = new Element_HTML('<div ' . $product_type_hidden_checked . ' class="product_type_hidden'.$form_slug.'_'.$field_id.'-0">');


            $product_type_default = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default']))
                $product_type_default = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_default'];

            $product_type = apply_filters( 'default_product_type', 'simple' );

            $product_type_selector = apply_filters( 'product_type_selector', array(
                'simple'   => __( 'Simple product', 'woocommerce' ),
                'grouped'  => __( 'Grouped product', 'woocommerce' ),
                'external' => __( 'External/Affiliate product', 'woocommerce' ),
                'variable' => __( 'Variable product', 'woocommerce' )
            ), $product_type );




            $type_box = '<label for="product-type"><p>Default Product Type</p><select id="product-type" name="buddyforms_options[buddyforms]['.$form_slug.'][form_fields]['.$field_id.'][product_type_default]"><optgroup label="' . __( 'Product Type', 'woocommerce' ) . '">';

            foreach ( $product_type_selector as $value => $label ) {
                $type_box .= '<option value="' . esc_attr( $value ) . '" ' . selected( $product_type_default, $value, false ) .'>' . esc_html( $label ) . '</option>';
            }

            $type_box .= '</optgroup></select></label>';

            $form_fields['full']['product_type_default'] = new Element_HTML($type_box);

            $product_type_options = apply_filters( 'product_type_options', array(
                'virtual' => array(
                    'id'            => '_virtual',
                    'wrapper_class' => 'show_if_simple',
                    'label'         => __( 'Virtual', 'woocommerce' ),
                    'description'   => __( 'Virtual products are intangible and aren\'t shipped.', 'woocommerce' ),
                    'default'       => 'no'
                ),
                'downloadable' => array(
                    'id'            => '_downloadable',
                    'wrapper_class' => 'show_if_simple',
                    'label'         => __( 'Downloadable', 'woocommerce' ),
                    'description'   => __( 'Downloadable products give access to a file upon purchase.', 'woocommerce' ),
                    'default'       => 'no'
                )
            ) );

            foreach ( $product_type_options as $key => $option ) {
                $product_type_option_value  = isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_options'][esc_attr( $option["id"] )] ) ? $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_type_options'][esc_attr( $option["id"] )] : '';
                $form_fields['full'][$key]  = new Element_Checkbox($option['description'] ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_type_options][". esc_attr( $option['id'] ) ."]",array($option['id'] => esc_html( $option['label'] ) ),array('id' => esc_attr( $option['id']), 'value' => $product_type_option_value  ));
            }

            $form_fields['full']['hr2'] = new Element_HTML('<hr>');

            $form_fields['full']['product_type_default_div_end'] = new Element_HTML('</div>');

            $product_sku = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sku']))
                $product_sku = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sku'];
            $form_fields['full']['product_sku']		= new Element_Checkbox('<b>' . __('SKU Field', 'buddyforms') . '</b>' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_sku]",array('hidden' => __('Hide', 'buddyforms'), "Required" => __('Required', 'buddyforms') ),array('inline' => 1, 'id' => 'product_sku'.$form_slug.'_'.$field_id , 'value' => $product_sku));


            $product_regular_price = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_regular_price']))
                $product_regular_price = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_regular_price'];
            $form_fields['full']['product_regular_price']		= new Element_Checkbox('<b>' . __('Regular Price', 'buddyforms') . '</b>' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_regular_price]",array('hidden' => __('Hide', 'buddyforms'), "Required" => __('Required', 'buddyforms') ),array('inline' => 1, 'id' => 'product_regular_price'.$form_slug.'_'.$field_id, 'value' => $product_regular_price));

            $product_sales_price = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sales_price']))
                $product_sales_price = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sales_price'];
            $form_fields['full']['product_sales_price']		= new Element_Checkbox('<b>' . __('Sales Price', 'buddyforms') . '</b>'  ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_sales_price]",array('hidden' => __('Hide', 'buddyforms'), "Required" => __('Required', 'buddyforms') ),array('inline' => 1, 'id' => 'product_sales_price'.$form_slug.'_'.$field_id , 'value' => $product_sales_price));

            $product_sales_price_dates = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sales_price_dates']))
                $product_sales_price_dates = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_sales_price_dates'];
            $form_fields['full']['product_sales_price_dates']		= new Element_Checkbox('<b>' . __('Sales Price Date', 'buddyforms') . '</b>'  ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_sales_price_dates]",array('hidden' => __('Hide', 'buddyforms'), "Required" => __('Required', 'buddyforms') ),array('inline' => 1, 'id' => 'product_sales_price_dates'.$form_slug.'_'.$field_id, 'value' => $product_sales_price_dates));


            break;
        case 'Inventory':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Inventory');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_inventory');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;
        case 'Shipping':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Shipping');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_shipping');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            break;
        case 'Linked-Products':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Linked Products');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_linked_products');

            $form_fields['right']['type']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            $product_up_sales = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_up_sales']))
                $product_up_sales = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_up_sales'];
            $form_fields['full']['product_up_sales']		= new Element_Checkbox('' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_up_sales]",array('hidden' => '<b>' .__('Hide the Up-Sales', 'buddyforms') . '</b>'),array('id' => 'product_up_sales'.$form_slug.'_'.$field_id , 'value' => $product_up_sales));

            $product_cross_sales = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_cross_sales']))
                $product_cross_sales = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['product_cross_sales'];
            $form_fields['full']['product_cross_sales']		= new Element_Checkbox('' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][product_cross_sales]",array('hidden' => '<b>' .__('Hide the Cross Sales', 'buddyforms') . '</b>'),array('id' => 'product_cross_sales'.$form_slug.'_'.$field_id, 'value' => $product_cross_sales));


            break;
        case 'Attributes':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Attributes');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", '_bf_wc_attributes');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));


            $taxonomies = buddyforms_taxonomies($form_slug);
            $bf_wc_attributes_tax = Array();
            foreach($taxonomies as $key => $taxonomie){
                if(substr($taxonomie, 0, 3) == 'pa_')
                    $bf_wc_attributes_tax[$taxonomie] = $taxonomie;
            }

            $bf_wc_attributes_pa = false;
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['_bf_wc_attributes_pa']))
                $bf_wc_attributes_pa = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['_bf_wc_attributes_pa'];
            $form_fields['left']['_bf_wc_attributes_pa'] 		= new Element_Checkbox('<b>' . __('Attribute Taxonomies', 'buddyforms') . '</b><p><smal>Select the Attribute Taxonomies you want to include. These are the attributes you have created under Product/Attributes</smal></p>', "buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][_bf_wc_attributes_pa]", $bf_wc_attributes_tax, array('value' => $bf_wc_attributes_pa));

            $attr_new_custom_field = 'false';
            if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['attr_new_custom_field']))
                $attr_new_custom_field = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['attr_new_custom_field'];
            $form_fields['left']['attr_new_custom_field']	= new Element_Checkbox('<b>'.__('Custom Attribute', 'buddyforms').'</b> <p><smal>This is the same as the Custom Attributes in the Product edit Screen</smal></p>' ,"buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][attr_new_custom_field]",array('attr_new' => '<b>' .__('User can create new custom fields ', 'buddyforms') . '</b>'),array('value' => $attr_new_custom_field));

            break;
        case 'Product-Gallery':

            unset($form_fields);
            $form_fields['right']['name']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][name]", 'Product Gallery');
            $form_fields['right']['slug']		= new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][slug]", 'bf_product_gallery');

            $form_fields['right']['type']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][type]", $field_type);
            $form_fields['right']['order']		    = new Element_Hidden("buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][order]", $field_position, array('id' => 'buddyforms/' . $form_slug .'/form_fields/'. $field_id .'/order'));

            break;

    }


    return $form_fields;
}
add_filter('buddyforms_form_element_add_field','buddyforms_woocommerce_create_new_form_builder_form_element',1,5);

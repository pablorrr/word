<?php

/*
 * TODO : DODAC INFO O FUNCKJONALANOSCI PLIKU
 * 
 */
//altewrnatywa  wp dla wploaded,
//https://www.mootpoint.org/blog/woocommerce-hook-product-updated-added/  
// woocommerce_update_product dziala od wc 3.0

add_action('wp_loaded', 'fixolablesAddProduct',10);
add_action('woocommerce_update_product',  'fixolablesAddProduct', 11 , 1);

function fixolablesAddProduct($id) {
   
           if (!class_exists('WooCommerce')) {
                return false;
            }
          
             global $wpdb;
            $statuses = array_keys(wc_get_order_statuses());
            $statuses = implode( "','", $statuses );
            
            // Getting last Order ID (max value)
            $results = $wpdb->get_col( "
        SELECT MAX(ID) FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'shop_order'
        AND post_status IN ('$statuses')
    " );
            
            
            $results = reset($results);
            
            $latest_order_id = $results;
            
            // Last order ID
            $order = wc_get_order( $latest_order_id ); // Get an instance of the WC_Order object
         
             //TODO : get thumbnail
            //https://stackoverflow.com/questions/28179558/echo-woocommerce-product-thumbnail
            
           /* if( $items ) {
                   
                    foreach( $items as $item ){
                    $id = isset( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
                    $product = wc_get_product( $id );
                    $order_thumb = $product->get_image();
                    //continue;
                }
            }
            echo 'thumbnail test  var_dump($order_thumb );  ';
            var_dump($order_thumb );*/
            
            $items = $order->get_items();
            //crerate desciption array 
          foreach ($items as $item) {
              
                $product_id = $item['product_id'];
                $product_instance = wc_get_product($product_id);
                //to chcek metadata exists
                $product_metada_check_by_key = $product_instance -> meta_exists('_labelco_prod_id');
          }
             //echo 'var_dump( $product_metadacheckbykey) ';
            // var_dump( $product_metada_check_by_key);
             if ( $product_metada_check_by_key === true){
                 return false;
             }
             
           $indx = 0;
           
            //TODO : get description
            //https://stackoverflow.com/questions/19763915/woocommerce-get-the-product-description-by-product-id
            
            $order_prod_name =[];
            $meta_prod_arr =[];
            $order_prod_desc=[];
           
            
            foreach ( $items as $item_id => $item_data ) {
                
                $product_id =$item_data ['product_id'];
                $product_obj = wc_get_product($product_id);
                
                $order_prod_desc[$indx]['labelco_order_full_desc'] = $product_obj->get_description();
                $order_prod_desc[$indx]['labelco_order_short_desc'] = $product_obj->get_short_description();
                $order_prod_name[$indx]['labelco_order_prod_name'] = $item_data->get_name();
                $meta_prod_arr[$indx]  = unserialize(wc_get_order_item_meta($item_id, 'labelco_custom_parameters', true));
                $meta_prod_arr[$indx]['labelco_price'] = wc_get_order_item_meta($item_id, '_line_total', true);
               
                $indx++;
            }
            
           $prodNameMetaArrMerged = [];
           
           foreach ($meta_prod_arr  as  $single_meta_key => $single_meta_val){
             
               $prodNameMetaArrMerged[$single_meta_key] =
                     array_merge($single_meta_val , $order_prod_name[$single_meta_key], $order_prod_desc[$single_meta_key]);
                  
                }
              
      
                if( empty(  $prodNameMetaArrMerged ) ){
                   return false;
                }
                
                (int)$postTitleId = 1;
			
      /*add new product post*/
                
           foreach( $prodNameMetaArrMerged as $singleMetaProd ){
               
               $labelco_ord_no =  (string)$singleMetaProd['prod_id']  ?   (string)$singleMetaProd['prod_id'] :  (string)$postTitleId;
                
                $args = [
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'meta_query'      => [
                        [
                            'key'          => '_labelco_prod_id',
                            'value'        =>  $labelco_ord_no.(string)$postTitleId,
                            'meta_compare' => '=',
                        ],
                    ]
                ];
                // @codingStandardsIgnoreEnd
                
                $labelco_prod_test = new WP_Query( $args );
                // TODO: sprawdzic czy nie mozna zastapic te funkcjonalnosci antyduplikacji poprzez: 
                //https://developer.wordpress.org/reference/functions/metadata_exists/
            //to be sure no additional posts before add
                if (!empty($labelco_prod_test->posts)){
                    //return false;
                    
                    continue;
                }
                else{
            $labelcoProdTitle = (string)$singleMetaProd['labelco_order_prod_name'] . '  - ORDER NO -  '.  $labelco_ord_no . ' - PROD. NO -   ' .(string)$postTitleId .'  ';
            $post_data  = array (
                'post_title'  =>$labelcoProdTitle,
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => $singleMetaProd['labelco_order_full_desc']  .' ',
                'post_excerpt' => $singleMetaProd['labelco_order_short_desc']  . ' ',
                'meta_input' => array(          '_width'                  =>isset( $singleMetaProd['size_width'] )  ?  $singleMetaProd['size_width']   : null  ,
                                                                   '_height'                 =>isset ( $singleMetaProd['size_height'] ) ?   $singleMetaProd['size_height']  : null ,
                                                                   '_visibility'              => 'visible',//alt hidden visible
                                                                   '_stock_status'      =>  'instock',
                                                                    '_regular_price '  => isset($singleMetaProd['labelco_price'] )  ?   $singleMetaProd['labelco_price'] :   null  ,
                                                                   '_price'                    =>  isset($singleMetaProd['labelco_price'] )  ?   $singleMetaProd['labelco_price'] :   null  ,
                                                                   '_sku'                       =>  $labelco_ord_no.(string)$postTitleId ,
                                                                   '_product_attributes' =>array(
                                                                        array(
                                                                            'name' => 'Size', // parameter for custom attributes
                                                                            'visible' => true, // default: false
                                                                             'value' =>  (string)$singleMetaProd["size_height"]. ' x ' . $singleMetaProd["size_width"]
                                                                        ),//size attr
                                                                       array(
                                                                            'name' => 'Quantity', // parameter for custom attributes
                                                                            'visible' => true, // default: false
                                                                            'value' =>  (int)$singleMetaProd["quantity"]
                                                                        ),//quantity attr
                                                                      ),//prod attr
                                                                   '_purchase_note' =>  '   ',
                                                                   '_manage_stock  '=> 'yes'
                                                         )//meta input- built in
                 ) ;//arr post data
                
                    
                   // create product
               
                   $id = wp_insert_post( $post_data );
                   //add extra key to create quantity meta
                   update_post_meta($id,'_labelco_prod_quantity', (int)$singleMetaProd["quantity"]);
                   
                   //add extra key to create size
                   update_post_meta($id,'_labelco_prod_size', (string)$singleMetaProd["size_height"]. ' x ' . $singleMetaProd["size_width"]);
                   
                   //add extra key to make unique product to compare and avoid  duplication
                   update_post_meta($id,'_labelco_prod_id',  $labelco_ord_no.(string)$postTitleId);
                   
                   //create extra spare price to avoid dissapearing price when  a new prod cat added
                   update_post_meta($id,'_labelco_prod_spare_price',  $singleMetaProd['labelco_price']);
                   
                   apply_filters( 'woocommerce_product_get_regular_price', 'custom_dynamic_regular_price', 10, 2 );
                   apply_filters( 'woocommerce_product_variation_get_regular_price', 'custom_dynamic_regular_price', 10, 2 );
                    //   https://devnetwork.io/add-woocommerce-product-programmatically/
                    
             (int)$postTitleId++;
                }//else  arr not empty
            }//endforeach
        

}
//remove_action ('wp', 'fixolablesAddProduct');


//https://stackoverflow.com/questions/48763989/set-product-sale-price-programmatically-in-woocommerce-3

//fix prod problem trial - genrating proce programtically
// Generating dynamically the product "regular price"
add_action('wp_loaded', 'add_custom_filter_callbacks',10);

function add_custom_filter_callbacks(){
    
        add_filter( 'woocommerce_product_get_regular_price', 'custom_dynamic_regular_price', 10, 2 );
        add_filter( 'woocommerce_product_variation_get_regular_price', 'custom_dynamic_regular_price', 10, 2 );
        
                function custom_dynamic_regular_price( $regular_price, $product ) {
                    if( empty($regular_price) || $regular_price == 0 )
                        return $product->get_price();
                        else
                            return $regular_price;
                    }
}

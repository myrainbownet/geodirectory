<?php get_header(); 


global $term,$post,$current_term,$wp_query, $gridview_columns;
//global $wp_query; echo $wp_query->request;
$gd_post_type = geodir_get_current_posttype();
$post_type_info = get_post_type_object( $gd_post_type );

$add_string_in_title = __('All',GEODIRECTORY_TEXTDOMAIN).' ';
if(isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite'){	
	$add_string_in_title = __('My Favourite',GEODIRECTORY_TEXTDOMAIN).' ';
}

$list_title = $add_string_in_title.$post_type_info->labels->name;
$single_name = $post_type_info->labels->singular_name;

$taxonomy = geodir_get_taxonomies( $gd_post_type , true);

if( !empty($term) )
{	$current_term = get_term_by('slug',$term,$taxonomy[0]);
	if(!empty($current_term))
		$list_title .= __(' in',GEODIRECTORY_TEXTDOMAIN). " '". ucwords( $current_term->name )."'";
	else
	{
		if(count($taxonomy) > 1)
		{
			$current_term = get_term_by('slug',$term,$taxonomy[1]);
			if(!empty($current_term))
				$list_title .= __(' in',GEODIRECTORY_TEXTDOMAIN). " '". ucwords( $current_term->name )."'";
		}
	}	
}
	
if(is_search())
{
	$list_title = __('Search',GEODIRECTORY_TEXTDOMAIN).' '.$post_type_info->labels->name. __(' For :',GEODIRECTORY_TEXTDOMAIN)." '".get_search_query()."'";

}	
//$current_term = $wp_query->get_queried_object();

?>

<div id="geodir_wrapper">
	
    
    
    <?php if(get_option('geodir_show_listing_top_section')) { ?>
    
    <div class="geodir_full_page clearfix">
   	    <?php dynamic_sidebar('geodir_listing_top');?>
	</div><!-- clearfix ends here-->
    
    <?php } ?>
    
    <?php geodir_breadcrumb(); ?>
    
    <h1><?php echo apply_filters('geodir_listing_page_title',wptexturize($list_title)); ?></h1>
    
    
         
				 <?php
				 if(isset($current_term->term_id) && $current_term->term_id != ''){
					
					$term_desc = term_description( $current_term->term_id, $gd_post_type.'_tags' ) ;
					$saved_data = stripslashes(get_tax_meta($current_term->term_id,'ct_cat_top_desc', false, $gd_post_type));
					if($term_desc && !$saved_data){ $saved_data = $term_desc;}
					$cat_description =  apply_filters( 'the_content', $saved_data );
					if($cat_description){?>
					
						<div class="term_description"><?php echo $cat_description;?></div> <?php
					}
					
				}
				?>
	
    
    <div class="clearfix geodir-common">
    	
        <?php if( get_option('geodir_show_listing_left_section') ) { ?> 
        <div class="geodir-onethird gd-third-left" <?php if($width = get_option('geodir_width_listing_left_section') ) { echo 'style="width:'.$width.'%;"'; } ?> >
           <div class="geodir-content-left">
		   <?php dynamic_sidebar('geodir_listing_left_sidebar');?>
           </div>
        </div>
        <?php } ?>
        
        <div class="geodir-onethird gd-third-middle" <?php if($width = get_option('geodir_width_listing_contant_section') ) { echo'style="width:'.$width.'%;"';} ?> >
       		<div class="geodir-content-content">
					
			<div class="clearfix">
			<?php do_action('geodir_before_listing'); ?>
			</div>
			
			
			<?php
			//do_action('geodir_tax_sort_options');
			$listing_view = get_option('geodir_listing_view');
			
			if(strstr($listing_view,'gridview')){
				
				$gridview_columns = $listing_view;
				
				$listing_view_exp = explode('_',$listing_view);
				
				$listing_view = $listing_view_exp[0];
				
			}
			
			$new_days = get_option('geodir_listing_new_days');
			
			
			
			geodir_get_template_part('listing',$listing_view);
			
			do_action('geodir_pagination');
			
			do_action('geodir_after_listing');
		?>
        	</div>
      	</div>
        
        
        <?php
	    	
		 if( get_option('geodir_show_listing_right_section') ) { ?> 
        <div class="geodir-onethird gd-third-right" <?php if($width = get_option('geodir_width_listing_right_section') ) { echo 'style="width:'.$width.'%;"';} ?> >
        	<div class="geodir-content-right">
            <?php   
			dynamic_sidebar('geodir_listing_right_sidebar');?>
            </div>
        </div>
        <?php  } ?>
        
    </div> 
    
    
    <?php if(get_option('geodir_show_listing_bottom_section')) { ?>
    
    <div class="geodir_full_page clearfix">
   	    <?php dynamic_sidebar('geodir_listing_bottom');?>
	</div><!-- clearfix ends here-->
    
    <?php } ?>   
    
</div> 
<?php get_footer();  
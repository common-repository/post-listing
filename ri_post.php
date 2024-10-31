<?php
/*
Plugin Name: Post Listing
Description: Display list and grid of posts.
Tags: post listing, posts, category, type, tag
Author URI: https://www.inkpack.dk/
Author: Kjeld Hansen
Text Domain: post_listing
Requires at least: 4.0
Tested up to: 4.4.2
Version: 1.0
*/


 if ( ! defined( 'ABSPATH' ) ) exit; 
add_action('admin_menu','post_listing_admin_menu');
function post_listing_admin_menu() { 
    add_menu_page(
		"Post Listing",
		"Listing",
		8,
		__FILE__,
		"post_listing_admin_menu_list",
		plugins_url( 'img/plugin-icon.png', __FILE__) 
	); 
}

function post_listing_admin_menu_list(){
	include 'post-listing-admin.php';
}


//add_action( 'admin_head', 'post_listing_admin_css' );
add_action( 'admin_enqueue_scripts', 'post_listing_admin_css' );
function post_listing_admin_css(){
	wp_register_style( 'post_listing_admin_wp_admin_css', plugins_url( '/css/admin.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'post_listing_admin_wp_admin_css' );	
}

if (!shortcode_exists('postList')) {
	add_shortcode('postList', 'post_listing_ri_list_posts');
}

//[postList type='post' cat='23' tag='24' ordby='date' ord='asc' count='10' offset='0' temp='t1' hide='date,author' exrpt='50']

if (!function_exists('post_listing_ri_list_posts')){
function post_listing_ri_list_posts($args){
	$licol = 3; if($class=='dgg'){ $licol = 12; $class .= ' rinp'; } 
	$riid = '';
	if($args[temp]=='t1'){
		$riid = 'ripl_template1';
		$ricss = plugins_url( '/css/t1.css', __FILE__);
	}
	else if($args[temp]=='t2'){
		$riid = 'ripl_template2';
		$ricss = plugins_url( '/css/t2.css', __FILE__);
	}
	
	wp_enqueue_style( 'style-name', $ricss );
	?>
    
	   	<ul class="postsri" id="<?php echo $riid; ?>">
            	<?php
					$custom_args = array(
					  'post_type' => $args[type],
					  'posts_per_page' => $args[count],
					  'tag' => $args[tag],
					  'category_name' => $args[cat],
					  'offset' => $args[offset],
					  'orderby' => $args[ordby],
					  'order'   => $args[ord],
					);
					$custom_query = new WP_Query( $custom_args );
			    ?>
              <?php if ( $custom_query->have_posts() ) : ?>
              <?php $ric=0; while ( $custom_query->have_posts() ) : $custom_query->the_post(); 
			  	$ric++;
				$rimeta = get_post_meta(get_the_id());
			  	 ?>
		   
            <?php
			if($args[temp]=='t1'){  ?>
				 <!-- Template 1 -->
                <li> 
                    <span><a class="" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) {    the_post_thumbnail(array(150,150));	} ?></a></span>
                    <h2><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <label>Posted by : <?php echo get_the_author(); ?> | Posted on : <?php echo get_the_date(); ?></label>
                    <div class="riexcerpt">
                      <p><?php ripl_excerpt($args[exrpt]); ?></p>
                    </div>
                </li>
			<?php }
			else if($args[temp]=='t2'){  ?>
				<!-- Template 2 -->
                <li> 
                    <span><a class="" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) {    the_post_thumbnail(array(150,150));	} ?></a></span>
                    <div class="postdesri">
                        <h2><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                       <label>Posted by : <?php echo get_the_author(); ?> | Posted on : <?php echo get_the_date(); ?></label>
                        <div class="riexcerpt">
                            <p><?php ripl_excerpt($args[exrpt]); ?></p>
                        </div>
                    </div>
                </li>
			<?php } ?>
           
		   <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
			<?php else:  ?>
            <li><p><?php _e( 'Sorry, no posts available.' ); ?></p></li>
            <?php endif; ?>
            </ul>
       
        <?php
}
}



function ripl_excerpt($charlength=50) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}

/*

$qr1 = array(); $qr2 = array(); $qr3 = array();  $qr4 = array(); $qr = '';
	
	if(isset($_GET['branch']) && $_GET['branch']!=''){ $qr1 = array( 'key'  => 'wpcf-branch', 'value'     => $_GET['branch'], 'compare'   => '=', 'type'      => 'BINARY',	) ;  }
	if(isset($_GET['qy']) && $_GET['qy']!=''){ $qr2 = array( 'key'  => 'wpcf-yearsem', 'value'     => $_GET['qy'], 'compare'   => '=', 'type'      => 'BINARY',	) ;  }
	if(isset($_GET['sem']) && $_GET['sem']!=''){ $qr3 = array( 'key'  => 'wpcf-semester', 'value'     => $_GET['sem'], 'compare'   => '=', 'type'      => 'BINARY',	) ;  }
	if(isset($_GET['univ']) && $_GET['univ']!=''){ $qr4 = array( 'key'  => 'wpcf-university', 'value'     => $_GET['univ'], 'compare'   => '=', 'type'      => 'BINARY',	) ;  }
	if(isset($_GET['branch']) || isset($_GET['qy']) || isset($_GET['sem'])){  $qr = '?branch='.$_GET['branch'].'&qy='.$_GET['qy'].'&sem='.$_GET['sem'].'&univ='.$_GET['univ'].'';	}
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	// WP_Query arguments
	$args = array (
	'post_type'              => array( 'questionpaper' ),
	'post_status'            => array( 'publish' ),
	'meta_query'             => array(
		$qr1,$qr2,$qr3,$qr4,
	),
	'order'                  => 'ASC',
	'orderby'                => 'title',
	'pagination'             => true,
	'posts_per_page'         => '15',
	'paged' => $paged 
);

*/

if (!function_exists('post_listing_related_posts_ri')){
function post_listing_related_posts_ri($id, $num=6){
	$categories = get_the_category($id);
	$tags = wp_get_post_tags($id); $tagarr = array(); 
	if ($tags) {
		foreach($tags as $tag)	$tagarr[] = $tag->term_id; 
	}

	if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category) {	$category_ids[] = $individual_category->term_id; }
	
	
	$args=array(
	'category__in' => $category_ids,
	//'tag__in'  => $tagarr,
	'post__not_in' => array($id),
	'posts_per_page'=> $num, 
	'caller_get_posts'=>1,
	'orderby'=>'rand' // Randomize the posts
	);
	
	$args2=array(
	//'category__in' => $category_ids,
	'tag__in'  => $tagarr,
	'post__not_in' => array($id),
	'posts_per_page'=> $num, 
	'caller_get_posts'=>1,
	'orderby'=>'rand' // Randomize the posts
	);
	
	$my_query = new wp_query( $args );
	if( $my_query->have_posts() ) {
	echo '<div class="main-content rirelposts">
	<h2><span>Similar Or Related</span><hr></h2>
	<ul class="postsri relpost">';
		while( $my_query->have_posts() ) {
		$my_query->the_post(); $rimeta = get_post_meta(get_the_id()); ?>
		<li class="">
             <div class="rinner">
                <a class="nopad col-xs-4" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) {    the_post_thumbnail(array(150,150));	} ?></a>
                <div class="caption nopad col-xs-8">
                    <span class="ridwn"> <a class="" title="Download" href="<?php the_permalink(); ?>">Download APK</a> </span>
                    <h3><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <a class="exrpt" href="<?php the_permalink(); ?>"><?php echo get_the_excerpt(); ?></a>
                </div>
            </div>
		</li>
		<?php }
	echo '</ul></div>';
	} }
	$post = $orig_post;
	wp_reset_query();
}
}



<?php
/*
Plugin Name: Visio Playlist
Plugin URI: http://www.innovativeroots.com/
Version: 1.1
Description: Visio Playlist is a plugin created to display top 10 songs/charts in a single playlist. You can create multiple playlist and songs by using this handy plugin.
Author: Muhammad Bilal
Author URI: http://www.innovativeroots.com/
Company: Innovative Roots
Company URI: http://www.innovativeroots.com/
* License: GPL2
*/
define( 'WP_PATH_PLUGIN', dirname(__FILE__) . '/' );
if ( ! defined( 'WP_VISIOPLAYLIST_BASENAME' ) )
	define( 'WP_VISIOPLAYLIST_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_VISIOPLAYLIST_PLUGIN_NAME' ) )
	define( 'WP_VISIOPLAYLIST_PLUGIN_NAME', trim( dirname( WP_VISIOPLAYLIST_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_VISIOPLAYLIST_PLUGIN_URL' ) )
	define( 'WP_VISIOPLAYLIST_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_VISIOPLAYLIST_PLUGIN_NAME );

//echo WP_VISIOPLAYLIST_PLUGIN_URL;
add_action('init', 'visio_playlist_init_method');

function visio_playlist_init_method() {
    wp_enqueue_script('jquery');
	
	if(is_admin())
	{		
		if(isset($_REQUEST['page']))
		{			
			if($_REQUEST['page']=="settings" || $_REQUEST['page']=="playlists" || $_REQUEST['page']=="listitems")
			{	
				wp_register_style('visio-admin',plugins_url('css/admin.css', __FILE__));
				wp_enqueue_style( 'visio-admin' );
			}
		}
	}
	else
	{			
		wp_register_style('visio-playlist',plugins_url('css/visio.css', __FILE__));
		wp_enqueue_style( 'visio-playlist' );
	}	
}
 
add_action( 'admin_menu', 'ir_visio_playlist_menu');
function ir_visio_playlist_menu() { 
	add_menu_page( 'Visio Playlist', 'Visio Playlist','', __FILE__,'ir_visio_playlist_display_settings' );	 
	add_submenu_page( __FILE__, 'Visio Playlist','Settings', 'manage_options','settings', ir_visio_playlist_display_settings );	 
	add_submenu_page( __FILE__, 'Visio Playlist','Playlists', 'manage_options','playlists', ir_visio_playlist_display_playlists );	 
	add_submenu_page( __FILE__, 'Visio Playlist','List Items', 'manage_options','listitems', ir_visio_playlist_display_listitems);	 
}

function ir_visio_playlist_display_settings(){
	include_once( WP_PATH_PLUGIN . 'includes/settings.php' );
}
function ir_visio_playlist_display_playlists(){
	include_once( WP_PATH_PLUGIN . 'includes/playlists.php' );
}
function ir_visio_playlist_display_listitems(){
	include_once( WP_PATH_PLUGIN . 'includes/listitems.php' );
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'visio_playlist_activation'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'visio_playlist_deactivation' );

function visio_playlist_activation() {
	global $wpdb;
    $table = $wpdb->prefix . 'visio_playlists';
	$table_lists = $wpdb->prefix . 'visio_playlists_lists';
	update_option('visio_playlist_artist_name', 'Artist');
	update_option('visio_playlist_title_name', 'Title');
	update_option('visio_playlist_first_column', 'artist');
	update_option('visio_playlist_order_by', 'artist');
	update_option('visio_playlist_show_headings', 1);
	$sql = "CREATE TABLE $table (
      `id_playlist` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `desc` text DEFAULT NULL,
	  `status` int(3) DEFAULT NULL,
      `add_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      UNIQUE KEY id_playlist (id_playlist)
    );";
	$qry = "CREATE TABLE $table_lists (
      `id_list` int(11) NOT NULL AUTO_INCREMENT,
	  `id_playlist` int(11) NOT NULL,
      `title` varchar(255) DEFAULT NULL,
      `desc` text DEFAULT NULL,
	  `rank` int(11) DEFAULT NULL,
	  `artist` varchar(255) DEFAULT NULL,
	  `url` varchar(255) DEFAULT NULL,
	  `status` int(3) DEFAULT NULL,
      `add_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      UNIQUE KEY `id_list` (`id_list`)
    );";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
	dbDelta( $qry );
	
}

function visio_playlist_deactivation() {
	global $wpdb;
    $table = $wpdb->prefix . 'visio_playlists';
	$table_lists = $wpdb->prefix . 'visio_playlists_lists';
	delete_option('visio_playlist_artist_name');
	delete_option('visio_playlist_title_name');
	delete_option('visio_playlist_first_column');
	delete_option('visio_playlist_order_by');
	delete_option('visio_playlist_show_headings');	
	$sql = "DROP TABLE IF EXISTS $table";	
	$qry = "DROP TABLE IF EXISTS $table_lists";
	$wpdb->query($sql);
	$wpdb->query($qry);
}

class visio_playlist_plugin extends WP_Widget {

	// constructor
	function visio_playlist_plugin() {
		parent::WP_Widget(false, $name = __('Visio Playlist', 'wp_widget_plugin') );
	}

	

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
      	$instance['title'] = strip_tags($new_instance['title']);      	
     	return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
        if ( $title )
		echo $before_title . $title . $after_title; 
		if (function_exists('visio_playlist')) echo visio_playlist(); 
		echo $after_widget; 
	}
	// widget form creation
	function form($instance) {	
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);		
		} else {
			 $title = '';
		}
	?>	
	
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
    	
	<?php 
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("visio_playlist_plugin");'));

function visio_playlist() {	
	global $wpdb;
    $table = $wpdb->prefix . 'visio_playlists';
	$table_lists = $wpdb->prefix . 'visio_playlists_lists';
	$show_headings = get_option('visio_playlist_show_headings');
	$col_artist = get_option('visio_playlist_artist_name');	
	$col_title = get_option('visio_playlist_title_name');
	$first_column = get_option('visio_playlist_first_column');
	$orderby = get_option('visio_playlist_order_by');
	
	
	$sSql = "SELECT 
	$table.id_playlist,
	$table.`name`,
	$table_lists.id_list,
	$table_lists.`title`,
	$table_lists.rank,
	$table_lists.artist,
	$table_lists.url
	FROM
	$table
	INNER JOIN $table_lists ON $table.id_playlist = $table_lists.id_playlist
	WHERE
	$table.`status` = 1 AND
	$table_lists.`status` = 1
	ORDER BY
	$table.id_playlist ASC,
	$table_lists.$orderby ASC
	";
	$results = $wpdb->get_results($sSql, ARRAY_A);
	$col1 = 'rank';
	$col2 = $first_column;
	$col2_called = (('artist' == $first_column) ? $col_artist : $col_title);
	$col3 = (('artist' == $col2) ? 'title' : 'artist');
	$col3_called = (('artist' == $col2) ? $col_title : $col_artist);
	$col4 = 'url';
	$output = "";
	$output .= '<div id="playlist" class="music">';
	$output .= '<div class="playlist">';
	foreach($results as $key => $values){
	if ($pid!=$results[$key]['id_playlist']) {
    if($key!=0) {
            $output .= '</div><div class="playlist">';
	}
	if($show_headings=="on") {
	$output .= '<div class="playlist_title">'.$results[$key]['name'].'</div>';
	}
	$output .= '<div class="playlist_headings">
				<div class="rank">'.$col1.'</div>			
				<div class="title">'.$col2_called.'</div>			
				<div class="artist">'.$col3_called.'</div>
				<div class="url">'.$col4.'</div>
			</div>';
		                                          
	}
	$output .= '<div class="playlist_items">
				<div class="rank">'.$results[$key][$col1].'</div>			
				<div class="title">'.$results[$key][$col2].'</div>			
				<div class="artist">'.$results[$key][$col3].'</div>
				<div class="url">'.$results[$key][$col4].'</div>
			</div>';
	$pid = $results[$key]['id_playlist'];
    
	}
    $output .= '</div>';
	$output .= '</div>';
	return $output;
}

add_shortcode('visio_playlist', 'visio_playlist');


?>
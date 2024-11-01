<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
	if(isset($_POST['submit']))
	{
		update_option('visio_playlist_show_headings',$_POST['visio_playlist_show_headings']);
		update_option('visio_playlist_artist_name',$_POST['visio_playlist_artist_name']);
		update_option('visio_playlist_title_name',$_POST['visio_playlist_title_name']);
		update_option('visio_playlist_first_column',$_POST['visio_playlist_first_column']);
		update_option('visio_playlist_order_by',$_POST['visio_playlist_order_by']);			
		$success = __('Playlist settings was successfully updated.', 'visio-playlist');
	}
	$visio_playlist_show_headings = get_option('visio_playlist_show_headings');
	$visio_playlist_artist_name = get_option('visio_playlist_artist_name');
	$visio_playlist_title_name = get_option('visio_playlist_title_name');
	$visio_playlist_first_column = get_option('visio_playlist_first_column');
	$visio_playlist_order_by = get_option('visio_playlist_order_by');
	
?>
<div class="wrap">
	<h2><?php _e('Visio Playlist > Settings', 'visio-playlist'); ?></h2>   
	<?php
	
    if (strlen($success) > 0)
	{
	?>
	<div class="updated fade">
		<p><strong><?php echo $success; ?></strong></p>
	</div>
	<?php } ?>    
    
    <form method="post" name="options">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="visio_playlist_show_headings"><?php _e('Show Headings', 'visio-playlist'); ?></label></th>
                    <td>
                        <fieldset>                           
                            <label for="visio_playlist_show_headings">						
                            <input name="visio_playlist_show_headings" type="checkbox" id="visio_playlist_show_headings" <?php if($visio_playlist_show_headings=="on") { ?> checked="cheked" <?php } ?> >
                            <?php _e('Show the song list headings.', 'visio-playlist'); ?>					
                        </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="visio_playlist_artist_name"><?php _e('Artist Name', 'visio-playlist'); ?></label></th>
                    <td>
                        <input name="visio_playlist_artist_name" type="text" id="visio_playlist_artist_name" value="<?php echo $visio_playlist_artist_name; ?>" class="regular-text">
                        <p class="description"><?php _e('What should the Artist column be named?', 'visio-playlist'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="visio_playlist_title_name"><?php _e('Title Name', 'visio-playlist'); ?></label></th>
                    <td>
                        <input name="visio_playlist_title_name" type="text" id="visio_playlist_title_name" value="<?php echo $visio_playlist_title_name; ?>" class="regular-text">
                        <p class="description"><?php _e('What should the Title column be named?', 'visio-playlist'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="visio_playlist_first_column"><?php _e('First Column', 'visio-playlist'); ?></label></th>
                    <td>
                        <select name="visio_playlist_first_column" id="visio_playlist_first_column">
                            <option value="artist" <?php if($visio_playlist_first_column=="artist") { ?> selected="selected" <?php } ?>>Artist</option>
                            <option value="title" <?php if($visio_playlist_first_column=="title") { ?> selected="selected" <?php } ?>>Title</option>
                        </select>
                        <p class="description"><?php _e('What should the first column contain?', 'visio-playlist'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="visio_playlist_order_by"><?php _e('Order By', 'visio-playlist'); ?></label></th>
                    <td>
                        <select name="visio_playlist_order_by" id="visio_playlist_order_by">
                            <option value="artist" <?php if($visio_playlist_order_by=="artist") { ?> selected="selected" <?php } ?>>Artist</option>
                            <option value="title" <?php if($visio_playlist_order_by=="title") { ?> selected="selected" <?php } ?>>Title</option>
                            <option value="rank" <?php if($visio_playlist_order_by=="rank") { ?> selected="selected" <?php } ?>>Rank</option>
                        </select>
                    	<p class="description"><?php _e('What should the song list be ordered by?', 'visio-playlist'); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php submit_button(); ?>
    </form>  
    
</div>
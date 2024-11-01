<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
	global $wpdb;
	$table = $wpdb->prefix . 'visio_playlists';
	$table_lists = $wpdb->prefix . 'visio_playlists_lists';
	
	$action = "insert";
	$form = array(
		'id_playlist' => '',		
		'title' => '',
		'desc' => '',
		'rank' => '',
		'artist' => '',
		'url' => '',
		'status' => ''
	);
	
	if (isset($_GET['action']) && $_GET['action']=="delete")
	{
		$dsql = "DELETE FROM `".$table_lists."` where id_list = ".(int) $_GET['lid'];				
		if($wpdb->query($dsql))
		{
			$success = __('Song was successfully deleted.', 'visio-playlist');	
		}
		else
		{
			$errors = __('There was an error while deleting values. Please try again later!', 'visio-playlist');
			$error_found = TRUE;
		}
	}
	if (isset($_POST['action']) && $_POST['action']=="insert")
	{
		check_admin_referer('visio_playlist_form_add');
		
		$form['id_playlist'] = isset($_POST['id_playlist']) ? $_POST['id_playlist'] : '';
		$form['title'] = isset($_POST['title']) ? $_POST['title'] : '';
		if ($form['title'] == '')
		{
			$errors = __('Please enter the Song Name.', 'visio-playlist');
			$error_found = TRUE;
		}
		
		$form['desc'] = isset($_POST['desc']) ? $_POST['desc'] : '';
		$form['rank'] = isset($_POST['rank']) ? $_POST['rank'] : '';
		$form['artist'] = isset($_POST['artist']) ? $_POST['artist'] : '';
		$form['url'] = isset($_POST['url']) ? $_POST['url'] : '';
		$form['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		
		if ($error_found == FALSE)
		{
			$sql = $wpdb->prepare(
			"INSERT INTO `".$table_lists."`
			(`id_playlist`, `title`, `desc`, `rank`, artist, url, `status`)
			VALUES(%s, %s, %s, %s, %s, %s, %s)",
			array($form['id_playlist'], $form['title'], $form['desc'], $form['rank'], $form['artist'], $form['url'], $form['status'])
			);	
			if($wpdb->query($sql))
			{
				$success = __('Song was successfully added.', 'visio-playlist');	
			}
			else
			{
				$errors = __('There was an error while inserting values. Please try again later!', 'visio-playlist');
				$error_found = TRUE;
			}			
			
		}
	}
	if (isset($_POST['action']) && $_POST['action']=="update")
	{
		check_admin_referer('visio_playlist_form_add');
	
		$form['id_playlist'] = isset($_POST['id_playlist']) ? $_POST['id_playlist'] : '';
		$form['title'] = isset($_POST['title']) ? $_POST['title'] : '';
		if ($form['title'] == '')
		{
			$errors = __('Please enter the Song Name.', 'visio-playlist');
			$error_found = TRUE;
		}
		
		$form['desc'] = isset($_POST['desc']) ? $_POST['desc'] : '';
		$form['rank'] = isset($_POST['rank']) ? $_POST['rank'] : '';
		$form['artist'] = isset($_POST['artist']) ? $_POST['artist'] : '';
		$form['url'] = isset($_POST['url']) ? $_POST['url'] : '';
		$form['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		
		if ($error_found == FALSE)
		{
			$sql = $wpdb->prepare(
			"UPDATE `".$table_lists."`
			SET `id_playlist` = %s,
			`title` = %s,
			`desc` = %s,
			`rank` = %s,
			`artist` = %s,
			`url` = %s,
			`status` = %s
			WHERE id_list = %d
			LIMIT 1",
			array($form['id_playlist'], $form['title'], $form['desc'], $form['rank'], $form['artist'], $form['url'], $form['status'], (int) $_POST['lid'])
			);
			
			if($wpdb->query($sql))
			{
				$success = __('Song was successfully updated.', 'visio-playlist');	
			}
			else
			{
				$errors = __('There was an error while updating values. Please try again later!', 'visio-playlist');
				$error_found = TRUE;
			}			
			
		}
	}
	if (isset($_GET['action']) && $_GET['action']=="edit")
	{
		$qry = "SELECT * FROM `".$table_lists."` where id_list = ".(int) $_GET['lid'];		
		$data = $wpdb->get_row($qry, ARRAY_A);
		$action = "update";
	}
	$sSql = "SELECT * FROM `".$table."` where `status` = 1 and 1=1 order by id_playlist asc";
	$results = $wpdb->get_results($sSql, ARRAY_A);
	
	$qry = "SELECT * FROM `".$table_lists."` where 1=1 order by id_list asc";
	$allData = $wpdb->get_results($qry, ARRAY_A);
?>
<div class="wrap">
	<h2><?php _e('Visio Playlist > Add/Edit List Item', 'visio-playlist'); ?></h2>
    <?php
    	if ($error_found == TRUE && isset($errors) == TRUE)
		{
	?>
    <div class="error fade">
        <p><strong><?php echo $errors; ?></strong></p>
    </div>
	<?php
	}
    if ($error_found == FALSE && strlen($success) > 0)
	{
	?>
	<div class="updated fade">
		<p><strong><?php echo $success; ?></strong></p>
	</div>
	<?php } ?>    
    
    <form action="" title="add_playlist_item" id="add_playlist_item" method="post">
    <table class="form-table">
        <tbody>
           <tr valign="top">
                <th scope="row"><label for="playlist"><?php _e('Playlist', 'visio-playlist'); ?></label></th>
                <td>
                	<select name="id_playlist" class="regular-text" style="width: 25em;">
                    	<?php foreach($results as $result) { ?>
                        <?php if($result['id_playlist']==$data['id_playlist']) { ?>
                    	<option value="<?php  echo $result['id_playlist']; ?>" selected="selected"><?php  echo $result['name']; ?></option>
                        <?php } else { ?>
                       	<option value="<?php  echo $result['id_playlist']; ?>"><?php  echo $result['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </td>
            </tr>            
            <tr valign="top">
                <th scope="row"><label for="title"><?php _e('Title', 'visio-playlist'); ?></label></th>
                <td><input name="title" type="text" id="title" value="<?php echo $data['title'] ?>" class="regular-text"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="description"><?php _e('Description', 'visio-playlist'); ?></label></th>
                <td><textarea name="desc" id="desc" rows="5" cols="100"><?php echo $data['desc'] ?></textarea>
                <p class="description">In a few words, explain what this playlist is about.</p></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="artist"><?php _e('Artist', 'visio-playlist'); ?></label></th>
                <td><input name="artist" type="text" id="artist" value="<?php echo $data['artist'] ?>" class="regular-text"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="rank"><?php _e('Rank', 'visio-playlist'); ?></label></th>
                <td><input name="rank" type="text" id="rank" value="<?php echo $data['rank'] ?>" class="regular-text"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="url"><?php _e('Url', 'visio-playlist'); ?></label></th>
                <td><input name="url" type="text" id="url" value="<?php echo $data['url'] ?>" class="regular-text"></td>
            </tr>   
            
            <tr valign="top">
                <th scope="row"><label for="status"><?php _e('Visibility', 'visio-playlist'); ?></label></th>
                <td>
                    <select name="status" id="status">
                    <option value="1" <?php if($data['status']==1 || $data['status']=="") { ?> selected="selected" <?php } ?> >Show</option>
                    <option value="0" <?php if($data['status']==0) { ?> selected="selected" <?php } ?>>Hide</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <input name="lid" id="lid" type="hidden" value="<?php echo $data['id_list'] ?>" />
    <input name="action" id="action" type="hidden" value="<?php echo $action ?>" />
    <?php wp_nonce_field('visio_playlist_form_add'); ?>
    <?php submit_button(); ?>
    </form>
    
    
    <?php
		
	?>
    
    <table id="ir-playlist" class="widefat fixed" cellspacing="0">
	<thead>
	<tr>
		<th scope="col" class="col-id">ID</th>
		<th scope="col" class="col-playlist">Song</th>
        <th scope="col" class="col-playlist">Artist</th>
        <th scope="col" class="col-playlist">Rank</th>
        <th scope="col" class="col-playlist">Url</th>
		<th scope="col" class="col-action">Action</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th scope="col" class="col-id">ID</th>
		<th scope="col" class="col-playlist">Song</th>
        <th scope="col" class="col-playlist">Artist</th>
        <th scope="col" class="col-playlist">Rank</th>
        <th scope="col" class="col-playlist">Url</th>
		<th scope="col" class="col-action">Action</th>
	</tr>
	</tfoot>
	<tbody id="the-playlist">
		<?php
			if(count($allData) > 0 ) {
		 		foreach($allData as $data) {
		?>
        <tr>
            <td class="col-id"><?php echo $data['id_list']; ?></td>
            <td class="col-playlist"><?php echo $data['title']; ?></td>
            <td class="col-playlist"><?php echo $data['artist']; ?></td>
            <td class="col-playlist"><?php echo $data['rank']; ?></td>
            <td class="col-playlist"><?php echo $data['url']; ?></td>
            <td class="col-action"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=listitems&amp;action=edit&amp;lid=<?php echo $data['id_list']; ?>"><?php _e('Edit', 'visio-playlist'); ?></a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=listitems&amp;action=delete&amp;lid=<?php echo $data['id_list']; ?>"><?php _e('Delete', 'visio-playlist'); ?></a></td>
		</tr>
    		<?php } ?>
        <?php }  else { ?>
        <tr><td colspan="6" align="center"><?php _e('No records available.', 'visio-playlist'); ?></td></tr>
		<?php } ?>
		</tbody>
</table>
</div>
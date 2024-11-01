<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
	global $wpdb;
	$table = $wpdb->prefix . 'visio_playlists';
	
	
	$action = "insert";
	$form = array(
		'name' => '',
		'desc' => '',
		'status' => ''
	);
	
	if (isset($_GET['action']) && $_GET['action']=="delete")
	{
		$dsql = "DELETE FROM `".$table."` where id_playlist = ".(int) $_GET['pid'];				
		if($wpdb->query($dsql))
		{			
			echo '<script type="text/javascript">window.location = "'.$_SERVER['PHP_SELF'].'?page=playlists&msgid=1";</script>';
			exit;
		}
		else
		{
			echo '<script type="text/javascript">window.location = "'.$_SERVER['PHP_SELF'].'?page=playlists&msgid=2";</script>';
			exit;			
		}
	}
	if (isset($_POST['action']) && $_POST['action']=="insert")
	{
		check_admin_referer('visio_playlist_form_add');
	
		$form['name'] = isset($_POST['name']) ? $_POST['name'] : '';
		if ($form['name'] == '')
		{
			$errors = __('Please enter the Playlist Name.', 'visio-playlist');
			$error_found = TRUE;
		}
		
		$form['desc'] = isset($_POST['desc']) ? $_POST['desc'] : '';		
		
		$form['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		
		if ($error_found == FALSE)
		{
			$sql = $wpdb->prepare(
			"INSERT INTO `".$table."`
			(`name`, `desc`, `status`)
			VALUES(%s, %s, %s)",
			array($form['name'], $form['desc'], $form['status'])
			);			
			
			if($wpdb->query($sql))
			{
				$success = __('Playlist was successfully added.', 'visio-playlist');	
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
	
		$form['name'] = isset($_POST['name']) ? $_POST['name'] : '';
		if ($form['name'] == '')
		{
			$errors = __('Please enter the Playlist Name.', 'visio-playlist');
			$error_found = TRUE;
		}
		
		$form['desc'] = isset($_POST['desc']) ? $_POST['desc'] : '';		
		
		$form['status'] = isset($_POST['status']) ? $_POST['status'] : '';
		
		if ($error_found == FALSE)
		{
			$sql = $wpdb->prepare(
			"UPDATE `".$table."`
			SET `name` = %s,
			`desc` = %s,
			`status` = %s
			WHERE id_playlist = %d
			LIMIT 1",
			array($form['name'], $form['desc'], $form['status'],(int) $_POST['pid'])
			);
			if($wpdb->query($sql))
			{
				$success = __('Playlist was successfully updated.', 'visio-playlist');	
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
		$qry = "SELECT * FROM `".$table."` where id_playlist = ".(int) $_GET['pid'];		
		$data = $wpdb->get_row($qry, ARRAY_A);
		$action = "update";
	}
	$sSql = "SELECT * FROM `".$table."` order by id_playlist asc";
	$results = $wpdb->get_results($sSql, ARRAY_A);
?>
<div class="wrap">
	<h2><?php _e('Visio Playlist > Add/Edit Playlist', 'visio-playlist'); ?></h2>
    <?php
    if ($_GET['msgid'] == 2)
	{
	?>    
	<div class="error fade">
        <p><strong><?php _e('There was an error while deleting values. Please try again later!', 'visio-playlist'); ?></strong></p>
    </div> 
    <?php } ?>
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
    <?php
    if ($_GET['msgid'] == 1)
	{
	?>
	<div class="updated fade">
		<p><strong><?php _e('Playlist was successfully deleted.', 'visio-playlist'); ?></strong></p>
	</div>
	<?php } ?>
    
    
    <form action="" name="add_playlist" id="add_playlist" method="post">
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="name"><?php _e('Name', 'visio-playlist'); ?></label></th>
                <td><input name="name" type="text" id="name" value="<?php echo $data['name'] ?>" class="regular-text"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="description"><?php _e('Description', 'visio-playlist'); ?></label></th>
                <td><textarea name="desc" id="desc" rows="5" cols="111"><?php echo $data['desc'] ?></textarea>
                <p class="description">In a few words, explain what this playlist is about.</p></td>
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
    <input name="pid" id="pid" type="hidden" value="<?php echo $data['id_playlist'] ?>" />
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
		<th scope="col" class="col-playlist">Playlist</th>
        <th scope="col" class="col-desc">Description</th>
		<th scope="col" class="col-action">Action</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<th scope="col" class="col-id">ID</th>
		<th scope="col" class="col-playlist">Playlist</th>
        <th scope="col" class="col-desc">Description</th>
		<th scope="col" class="col-action">Action</th>
	</tr>
	</tfoot>
	<tbody id="the-playlist">
		<?php
			if(count($results) > 0 ) {
		 		foreach($results as $data) {
		?>
        <tr>
            <td class="col-id"><?php echo $data['id_playlist']; ?></td>
            <td class="col-playlist"><?php echo $data['name']; ?></td>
            <td class="col-desc"><?php echo $data['desc']; ?></td>
            <td class="col-action"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=playlists&amp;action=edit&amp;pid=<?php echo $data['id_playlist']; ?>"><?php _e('Edit', 'visio-playlist'); ?></a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=playlists&amp;action=delete&amp;pid=<?php echo $data['id_playlist']; ?>"><?php _e('Delete', 'visio-playlist'); ?></a></td>
		</tr>
    		<?php } ?>
        <?php }  else { ?>
        <tr><td colspan="4" align="center"><?php _e('No records available.', 'visio-playlist'); ?></td></tr>
		<?php } ?>
		</tbody>
</table>
</div>
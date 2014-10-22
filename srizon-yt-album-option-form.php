<form action="admin.php?page=SrzYt-Albums&srzf=save" method="post">
	<?php SrizonYTUI::BoxHeader('box1', "Album Title", true);?>
	<div><input type="text" name="title" size="30" style="width:100%;" value="<?php echo $value_arr['title'];?>" /></div>
	<?php 
	SrizonYTUI::BoxFooter();
	SrizonYTUI::BoxHeader('box2', "Video Source Setup", true);
	?>
	<div>
		<div>Put your YouTube Username/Channel name:</div>
		<div>
			<input type="text" name="api_id" size="30" style="width:100%;" value="<?php echo $value_arr['api_id'];?>" />
		</div>
		<div>If your channel url is <em style="color:blue;">http://www.youtube.com/user/trailers</em> then the userid is <strong>trailers</strong></div>
		<br />
		<div>
			Use the value above as:
		</div>
		<div>
			<input type="radio" name="options[source]" value="userupload"<?php if($value_arr['source'] == 'userupload') echo ' checked="checked"';?> />Youtube Username and fetch user's uploads
		</div>
		<div>
			<input type="radio" name="options[source]" value="userfav"<?php if($value_arr['source'] == 'userfav') echo ' checked="checked"';?> />Youtube Username and fetch user's favorites
		</div>
		<div>
			<input type="radio" name="options[source]" value="usersub"<?php if($value_arr['source'] == 'usersub') echo ' checked="checked"';?> />Youtube Username and fetch user's subscribed videos
		</div>
		<div>
			You can also add Youtube <strong>playlists</strong> on the	<a href="http://www.srizon.com/wordpress-plugin/srizon-youtube-album">Pro version</a>
		</div>
	</div>
	<?php 
	SrizonYTUI::BoxFooter();
	SrizonYTUI::BoxHeader('box3', "Options", true);
	?>
	<table style="border-collapse:separate; border-spacing: 10px;">
		<tr>
			<td>
				<span class="label">Sync After Every # minutes</span>
			</td>
			<td>
				<input type="text" size="5" name="options[updatefeed]" value="<?php echo $value_arr['updatefeed'];?>" />
			</td>
		</tr>
		<tr>
			<td>
				<span class="label">Paginate After # Thumbs</span>
			</td>
			<td>
				<em>Available on the <a href="http://www.srizon.com/wordpress-plugin/srizon-youtube-album">Pro version</a></em>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label">Total Number of Videos</span>
			</td>
			<td>
				<input type="text" size="3" name="options[totalvid]" value="<?php echo $value_arr['totalvid'];?>" /> <em>Maximum 25 on this free version for unlimited videos get the <a href="http://www.srizon.com/wordpress-plugin/srizon-youtube-album">Pro version</a></em>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label">Layout <br /> <em>(Set Related Params Below)</em></span>
			</td>
			<td>
				<div>
					<input type="radio" name="options[liststyle]" value="fullpage"<?php if($value_arr['liststyle'] == 'fullpage') echo ' checked="checked"';?> />Thumb Grid (Optionally with Title Below)
				</div>
				<div>
					<input type="radio" name="options[liststyle]" value="description"<?php if($value_arr['liststyle'] == 'description') echo ' checked="checked"';?> />Thumb With Description (One in a row. Thumb on the left and Title+Description on the right)
				</div>
				<div>
					Another layout 'Responsive Slider' <em>Available on the <a href="http://www.srizon.com/wordpress-plugin/srizon-youtube-album">Pro version</a></em>
				</div>
			</td>
		</tr>
	</table>
<?php 
	SrizonYTUI::BoxFooter();
	SrizonYTUI::BoxHeader('box4', "'Thumb Grid' Layout Related Options", true);
?>
	<table  style="border-collapse:separate; border-spacing: 10px;">
		<tr>
			<td>Thumbs in a Row (Large Display):</td>
			<td>
				<input type="radio" name="options[largerow]" id="" value="1" <?php if($value_arr['largerow'] == '1') echo ' checked="checked"';?> /><span>1 </span>
				<input type="radio" name="options[largerow]" id="" value="2" <?php if($value_arr['largerow'] == '2') echo ' checked="checked"';?> /><span>2 </span>
				<input type="radio" name="options[largerow]" id="" value="3" <?php if($value_arr['largerow'] == '3') echo ' checked="checked"';?> /><span>3 </span>
				<input type="radio" name="options[largerow]" id="" value="4" <?php if($value_arr['largerow'] == '4') echo ' checked="checked"';?> /><span>4 </span>
				<input type="radio" name="options[largerow]" id="" value="5" <?php if($value_arr['largerow'] == '5') echo ' checked="checked"';?> /><span>5 </span>
			</td>
		</tr>
		<tr>
			<td>Thumbs in a Row (Small Display - Responsive theme):</td>
			<td>
				<input type="radio" name="options[smallrow]" id="" value="1" <?php if($value_arr['smallrow'] == '1') echo ' checked="checked"';?> /><span>1 </span>
				<input type="radio" name="options[smallrow]" id="" value="2" <?php if($value_arr['smallrow'] == '2') echo ' checked="checked"';?> /><span>2 </span>
				<input type="radio" name="options[smallrow]" id="" value="3" <?php if($value_arr['smallrow'] == '3') echo ' checked="checked"';?> /><span>3 </span>
			</td>
		</tr>
		<tr>
			<td>Thumb Padding:</td>
			<td>
				<input type="radio" name="options[thumbpadding]" id="" value="2" <?php if($value_arr['thumbpadding'] == '2') echo ' checked="checked"';?> /><span>2px </span>
				<input type="radio" name="options[thumbpadding]" id="" value="3" <?php if($value_arr['thumbpadding'] == '3') echo ' checked="checked"';?> /><span>3px </span>
				<input type="radio" name="options[thumbpadding]" id="" value="5" <?php if($value_arr['thumbpadding'] == '5') echo ' checked="checked"';?> /><span>5px </span>
				<input type="radio" name="options[thumbpadding]" id="" value="7" <?php if($value_arr['thumbpadding'] == '7') echo ' checked="checked"';?> /><span>7px </span>
				<input type="radio" name="options[thumbpadding]" id="" value="10" <?php if($value_arr['thumbpadding'] == '10') echo ' checked="checked"';?> /><span>10px </span>
			</td>
		</tr>
		<tr>
			<td>Show Title Below Thumbnail?:</td>
			<td>
				<input type="radio" name="options[showtitle]" id="" value="yes"<?php if($value_arr['showtitle'] == 'yes') echo ' checked="checked"';?> /><span>Yes </span>
				<input type="radio" name="options[showtitle]" id="" value="no"<?php if($value_arr['showtitle'] == 'no') echo ' checked="checked"';?> /><span>No </span>
			</td>
		</tr>
		<tr>
			<td>Title Box Height</td>
			<td>
				<input type="text" size="3" name="options[titleboxheight]" id="" value="<?php echo $value_arr['titleboxheight'];?>" /><span>px</span>
			</td>
		</tr>
		<tr>
			<td>Thumbnail corner rounding:</td>
			<td>
				<input type="radio" name="options[conrnerrounding]" id="" value="3"<?php if($value_arr['conrnerrounding'] == '3') echo ' checked="checked"';?> /><span>3px </span>
				<input type="radio" name="options[conrnerrounding]" id="" value=5"<?php if($value_arr['conrnerrounding'] == '5') echo ' checked="checked"';?> /><span>5px </span>
				<input type="radio" name="options[conrnerrounding]" id="" value="7"<?php if($value_arr['conrnerrounding'] == '7') echo ' checked="checked"';?> /><span>7px </span>
				<input type="radio" name="options[conrnerrounding]" id="" value="10"<?php if($value_arr['conrnerrounding'] == '10') echo ' checked="checked"';?> /><span>10px </span>
				<input type="radio" name="options[conrnerrounding]" id="" value="none"<?php if($value_arr['conrnerrounding'] == 'none') echo ' checked="checked"';?> /><span>No Rounding </span>
			</td>
		</tr>
		<tr>
			<td>Thumbnail Shadow:</td>
			<td>
				<input type="radio" name="options[thumbshadow]" id="" value="10l"<?php if($value_arr['thumbshadow'] == '10l') echo ' checked="checked"';?> /><span>10px light </span>
				<input type="radio" name="options[thumbshadow]" id="" value="5l"<?php if($value_arr['thumbshadow'] == '5l') echo ' checked="checked"';?> /><span>5px light </span>
				<input type="radio" name="options[thumbshadow]" id="" value="10d"<?php if($value_arr['thumbshadow'] == '10d') echo ' checked="checked"';?> /><span>10px dark </span>
				<input type="radio" name="options[thumbshadow]" id="" value="5d"<?php if($value_arr['thumbshadow'] == '5d') echo ' checked="checked"';?> /><span>5px dark </span>
				<input type="radio" name="options[thumbshadow]" id="" value="none"<?php if($value_arr['thumbshadow'] == 'none') echo ' checked="checked"';?> /><span>No shadow </span>
			</td>
		</tr>
		
		<tr>
			<td>Icon Overlay on Thumbnail:</td>
			<td>
				<input type="radio" name="options[iconoverlay]" id="" value="yes"<?php if($value_arr['iconoverlay'] == 'yes') echo ' checked="checked"';?> /><span>Show </span>
				<input type="radio" name="options[iconoverlay]" id="" value="no"<?php if($value_arr['iconoverlay'] == 'no') echo ' checked="checked"';?> /><span>Hide </span>
			</td>
		</tr>
	</table>
<?php
	SrizonYTUI::BoxFooter();
	SrizonYTUI::BoxHeader('box5', "'Thumb With Description' Layout Related Options", true);
?>
	<table  style="border-collapse:separate; border-spacing: 10px;">
		<tr>
			<td>Thumb/Description Ratio: <br/> <em>On smaller display Description will go below thumb</em></td>
			<td>
				<input type="radio" name="options[tdratio]" id="" value="2080"<?php if($value_arr['tdratio'] == '2080') echo ' checked="checked"';?> /><span>20-80 </span>
				<input type="radio" name="options[tdratio]" id="" value="2575"<?php if($value_arr['tdratio'] == '2575') echo ' checked="checked"';?> /><span>25-75 </span>
				<input type="radio" name="options[tdratio]" id="" value="3565"<?php if($value_arr['tdratio'] == '3565') echo ' checked="checked"';?> /><span>35-65 </span>
				<input type="radio" name="options[tdratio]" id="" value="5050"<?php if($value_arr['tdratio'] == '5050') echo ' checked="checked"';?> /><span>50-50 </span>
				<input type="radio" name="options[tdratio]" id="" value="100"<?php if($value_arr['tdratio'] == '100') echo ' checked="checked"';?> /><span>Description below thumb </span>
			</td>
		</tr>
		<tr>
			<td>Thumbnail corner rounding:</td>
			<td>
				<input type="radio" name="options[conrnerroundingd]" id="" value="3"<?php if($value_arr['conrnerroundingd'] == '3') echo ' checked="checked"';?> /><span>3px </span>
				<input type="radio" name="options[conrnerroundingd]" id="" value=5"<?php if($value_arr['conrnerroundingd'] == '5') echo ' checked="checked"';?> /><span>5px </span>
				<input type="radio" name="options[conrnerroundingd]" id="" value="7"<?php if($value_arr['conrnerroundingd'] == '7') echo ' checked="checked"';?> /><span>7px </span>
				<input type="radio" name="options[conrnerroundingd]" id="" value="10"<?php if($value_arr['conrnerroundingd'] == '10') echo ' checked="checked"';?> /><span>10px </span>
				<input type="radio" name="options[conrnerroundingd]" id="" value="none"<?php if($value_arr['conrnerroundingd'] == 'none') echo ' checked="checked"';?> /><span>No Rounding </span>
			</td>
		</tr>
		<tr>
			<td>Thumbnail Shadow:</td>
			<td>
				<input type="radio" name="options[thumbshadowd]" id="" value="10l"<?php if($value_arr['thumbshadowd'] == '10l') echo ' checked="checked"';?> /><span>10px light </span>
				<input type="radio" name="options[thumbshadowd]" id="" value="5l"<?php if($value_arr['thumbshadowd'] == '5l') echo ' checked="checked"';?> /><span>5px light </span>
				<input type="radio" name="options[thumbshadowd]" id="" value="10d"<?php if($value_arr['thumbshadowd'] == '10d') echo ' checked="checked"';?> /><span>10px dark </span>
				<input type="radio" name="options[thumbshadowd]" id="" value="5d"<?php if($value_arr['thumbshadowd'] == '5d') echo ' checked="checked"';?> /><span>5px dark </span>
				<input type="radio" name="options[thumbshadowd]" id="" value="none"<?php if($value_arr['thumbshadowd'] == 'none') echo ' checked="checked"';?> /><span>No shadow </span>
			</td>
		</tr>
		
		<tr>
			<td>Icon Overlay on Thumbnail:</td>
			<td>
				<input type="radio" name="options[iconoverlayd]" id="" value="yes"<?php if($value_arr['iconoverlayd'] == 'yes') echo ' checked="checked"';?> /><span>Show </span>
				<input type="radio" name="options[iconoverlayd]" id="" value="no"<?php if($value_arr['iconoverlayd'] == 'no') echo ' checked="checked"';?> /><span>Hide </span>
			</td>
		</tr>
		<tr>
			<td>Trim Description after # characters</td>
			<td><input type="text" size="4" name="options[trimdesc]" value="<?php echo $value_arr['trimdesc']?>" /></td>
		</tr>
	</table>
<?php
	SrizonYTUI::BoxFooter();
?>
<div>
	<span class="label"><?php wp_nonce_field('srz_yt_albums', 'srjyt_submit');?></span>
	<?php
	if(isset($value_arr['id'])){
		echo '<input type="hidden" name="id" value="'.$value_arr['id'].'" />';
	}
	?>
	<input type="submit" class="button-primary" name="submit" value="Save Album" />
</div>
	
</form>
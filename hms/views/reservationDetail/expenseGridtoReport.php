<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      reservation_id		</th>
 		<th width="80px">
		      room_id		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->reservation_id; ?>
		</td>
       		<td>
			<?php echo $row->room_id; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

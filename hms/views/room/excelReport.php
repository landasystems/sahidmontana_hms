<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      number		</th>
 		<th width="80px">
		      room_type_id		</th>
 		<th width="80px">
		      floor		</th>
 		<th width="80px">
		      bed		</th>
 		<th width="80px">
		      linked_room_id		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->number; ?>
		</td>
       		<td>
			<?php echo $row->room_type_id; ?>
		</td>
       		<td>
			<?php echo $row->floor; ?>
		</td>
       		<td>
			<?php echo $row->bed; ?>
		</td>
       		<td>
			<?php echo $row->linked_room_id; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

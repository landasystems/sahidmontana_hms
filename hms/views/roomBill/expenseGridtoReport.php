<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      room_id		</th>
 		<th width="80px">
		      room_number		</th>
 		<th width="80px">
		      date_bill		</th>
 		<th width="80px">
		      charge		</th>
 		<th width="80px">
		      processed		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      modified		</th>
 		<th width="80px">
		      created		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->room_id; ?>
		</td>
       		<td>
			<?php echo $row->room_number; ?>
		</td>
       		<td>
			<?php echo $row->date_bill; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       		<td>
			<?php echo $row->processed; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->modified; ?>
		</td>
       		<td>
			<?php echo $row->created; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

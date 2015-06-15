<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      reservation_id		</th>
 		<th width="80px">
		      guest_user_id		</th>
 		<th width="80px">
		      note		</th>
 		<th width="80px">
		      created		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      billing		</th>
 		<th width="80px">
		      col		</th>
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
			<?php echo $row->guest_user_id; ?>
		</td>
       		<td>
			<?php echo $row->note; ?>
		</td>
       		<td>
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->billing; ?>
		</td>
       		<td>
			<?php echo $row->col; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      code		</th>
 		<th width="80px">
		      guest_user_id		</th>
 		<th width="80px">
		      dp_by		</th>
 		<th width="80px">
		      amount		</th>
 		<th width="80px">
		      description		</th>
 		<th width="80px">
		      is_applied		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      created		</th>
 		<th width="80px">
		      modified		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->code; ?>
		</td>
       		<td>
			<?php echo $row->guest_user_id; ?>
		</td>
       		<td>
			<?php echo $row->dp_by; ?>
		</td>
       		<td>
			<?php echo $row->amount; ?>
		</td>
       		<td>
			<?php echo $row->description; ?>
		</td>
       		<td>
			<?php echo $row->is_applied; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->modified; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

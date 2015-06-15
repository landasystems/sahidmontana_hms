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
		      created		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      reserved_by		</th>
 		<th width="80px">
		      cp_name		</th>
 		<th width="80px">
		      cp_telephone_number		</th>
 		<th width="80px">
		      cp_note		</th>
 		<th width="80px">
		      date_from		</th>
 		<th width="80px">
		      date_to		</th>
 		<th width="80px">
		      billing_user_id		</th>
 		<th width="80px">
		      billing_note		</th>
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
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->reserved_by; ?>
		</td>
       		<td>
			<?php echo $row->cp_name; ?>
		</td>
       		<td>
			<?php echo $row->cp_telephone_number; ?>
		</td>
       		<td>
			<?php echo $row->cp_note; ?>
		</td>
       		<td>
			<?php echo $row->date_from; ?>
		</td>
       		<td>
			<?php echo $row->date_to; ?>
		</td>
       		<td>
			<?php echo $row->billing_user_id; ?>
		</td>
       		<td>
			<?php echo $row->billing_note; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

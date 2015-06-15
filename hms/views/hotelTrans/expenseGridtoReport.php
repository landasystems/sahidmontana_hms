<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      registration_id		</th>
 		<th width="80px">
		      created		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      total		</th>
 		<th width="80px">
		      refund		</th>
 		<th width="80px">
		      barcode		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->registration_id; ?>
		</td>
       		<td>
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->total; ?>
		</td>
       		<td>
			<?php echo $row->refund; ?>
		</td>
       		<td>
			<?php echo $row->barcode; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

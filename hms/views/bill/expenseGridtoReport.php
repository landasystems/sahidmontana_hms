<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      code		</th>
 		<th width="80px">
		      created		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      cash		</th>
 		<th width="80px">
		      charge		</th>
 		<th width="80px">
		      ca_user_id		</th>
 		<th width="80px">
		      refund		</th>
 		<th width="80px">
		      total		</th>
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
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->cash; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       		<td>
			<?php echo $row->ca_user_id; ?>
		</td>
       		<td>
			<?php echo $row->refund; ?>
		</td>
       		<td>
			<?php echo $row->total; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

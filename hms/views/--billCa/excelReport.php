<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      bill_id		</th>
 		<th width="80px">
		      bill_charge_id		</th>
 		<th width="80px">
		      charge		</th>
 		<th width="80px">
		      charge_less		</th>
 		<th width="80px">
		      description		</th>
 		<th width="80px">
		      created		</th>
 		<th width="80px">
		      created_user_id		</th>
 		<th width="80px">
		      modified		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->bill_id; ?>
		</td>
       		<td>
			<?php echo $row->bill_charge_id; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       		<td>
			<?php echo $row->charge_less; ?>
		</td>
       		<td>
			<?php echo $row->description; ?>
		</td>
       		<td>
			<?php echo $row->created; ?>
		</td>
       		<td>
			<?php echo $row->created_user_id; ?>
		</td>
       		<td>
			<?php echo $row->modified; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

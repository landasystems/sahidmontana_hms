<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      bill_charge_id		</th>
 		<th width="80px">
		      charge_additional_id		</th>
 		<th width="80px">
		      amount		</th>
 		<th width="80px">
		      charge		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->bill_charge_id; ?>
		</td>
       		<td>
			<?php echo $row->charge_additional_id; ?>
		</td>
       		<td>
			<?php echo $row->amount; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

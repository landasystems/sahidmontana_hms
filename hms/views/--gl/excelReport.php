<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      na_id		</th>
 		<th width="80px">
		      room_id		</th>
 		<th width="80px">
		      guest_user_id		</th>
 		<th width="80px">
		      charge_previous		</th>
 		<th width="80px">
		      charge		</th>
 		<th width="80px">
		      charge_settle		</th>
 		<th width="80px">
		      charge_balance		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->na_id; ?>
		</td>
       		<td>
			<?php echo $row->room_id; ?>
		</td>
       		<td>
			<?php echo $row->guest_user_id; ?>
		</td>
       		<td>
			<?php echo $row->charge_previous; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       		<td>
			<?php echo $row->charge_settle; ?>
		</td>
       		<td>
			<?php echo $row->charge_balance; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

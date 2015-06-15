<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      na_id		</th>
 		<th width="80px">
		      charge_additional_category_id		</th>
 		<th width="80px">
		      name		</th>
 		<th width="80px">
		      room_id		</th>
 		<th width="80px">
		      by		</th>
 		<th width="80px">
		      by_cc		</th>
 		<th width="80px">
		      by_cl		</th>
 		<th width="80px">
		      by_gl		</th>
 		<th width="80px">
		      by_bank		</th>
 		<th width="80px">
		      by_cash		</th>
 		<th width="80px">
		      cashier_user_id		</th>
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
			<?php echo $row->charge_additional_category_id; ?>
		</td>
       		<td>
			<?php echo $row->name; ?>
		</td>
       		<td>
			<?php echo $row->room_id; ?>
		</td>
       		<td>
			<?php echo $row->by; ?>
		</td>
       		<td>
			<?php echo $row->by_cc; ?>
		</td>
       		<td>
			<?php echo $row->by_cl; ?>
		</td>
       		<td>
			<?php echo $row->by_gl; ?>
		</td>
       		<td>
			<?php echo $row->by_bank; ?>
		</td>
       		<td>
			<?php echo $row->by_cash; ?>
		</td>
       		<td>
			<?php echo $row->cashier_user_id; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      charge_additional_category_id		</th>
 		<th width="80px">
		      name		</th>
 		<th width="80px">
		      charge		</th>
 		<th width="80px">
		      description		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->charge_additional_category_id; ?>
		</td>
       		<td>
			<?php echo $row->name; ?>
		</td>
       		<td>
			<?php echo $row->charge; ?>
		</td>
       		<td>
			<?php echo $row->description; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

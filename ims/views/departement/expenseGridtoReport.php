<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      name		</th>
 		<th width="80px">
		      address		</th>
 		<th width="80px">
		      city_id		</th>
 		<th width="80px">
		      phone		</th>
 		<th width="80px">
		      email		</th>
 		<th width="80px">
		      fax		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->name; ?>
		</td>
       		<td>
			<?php echo $row->address; ?>
		</td>
       		<td>
			<?php echo $row->city_id; ?>
		</td>
       		<td>
			<?php echo $row->phone; ?>
		</td>
       		<td>
			<?php echo $row->email; ?>
		</td>
       		<td>
			<?php echo $row->fax; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

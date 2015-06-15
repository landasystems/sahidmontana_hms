<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      client_name		</th>
 		<th width="80px">
		      client_logo		</th>
 		<th width="80px">
		      city_id		</th>
 		<th width="80px">
		      address		</th>
 		<th width="80px">
		      phone		</th>
 		<th width="80px">
		      email		</th>
 	</tr>
	<?php foreach($model as $row): ?>
	<tr>
        		<td>
			<?php echo $row->id; ?>
		</td>
       		<td>
			<?php echo $row->client_name; ?>
		</td>
       		<td>
			<?php echo $row->client_logo; ?>
		</td>
       		<td>
			<?php echo $row->city_id; ?>
		</td>
       		<td>
			<?php echo $row->address; ?>
		</td>
       		<td>
			<?php echo $row->phone; ?>
		</td>
       		<td>
			<?php echo $row->email; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

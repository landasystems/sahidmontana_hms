<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      date_na		</th>
 		<th width="80px">
		      global_by_cash		</th>
 		<th width="80px">
		      global_by_cc		</th>
 		<th width="80px">
		      global_by_gl		</th>
 		<th width="80px">
		      global_by_cl		</th>
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
			<?php echo $row->date_na; ?>
		</td>
       		<td>
			<?php echo $row->global_by_cash; ?>
		</td>
       		<td>
			<?php echo $row->global_by_cc; ?>
		</td>
       		<td>
			<?php echo $row->global_by_gl; ?>
		</td>
       		<td>
			<?php echo $row->global_by_cl; ?>
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

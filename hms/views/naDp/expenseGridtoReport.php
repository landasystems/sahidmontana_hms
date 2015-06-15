<?php if ($model !== null):?>
<table border="1">

	<tr>
		<th width="80px">
		      id		</th>
 		<th width="80px">
		      na_id		</th>
 		<th width="80px">
		      name		</th>
 		<th width="80px">
		      by_cash		</th>
 		<th width="80px">
		      by_cc		</th>
 		<th width="80px">
		      by_bank		</th>
 		<th width="80px">
		      by_gl		</th>
 		<th width="80px">
		      by_cl		</th>
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
			<?php echo $row->name; ?>
		</td>
       		<td>
			<?php echo $row->by_cash; ?>
		</td>
       		<td>
			<?php echo $row->by_cc; ?>
		</td>
       		<td>
			<?php echo $row->by_bank; ?>
		</td>
       		<td>
			<?php echo $row->by_gl; ?>
		</td>
       		<td>
			<?php echo $row->by_cl; ?>
		</td>
       	</tr>
     <?php endforeach; ?>
</table>
<?php endif; ?>

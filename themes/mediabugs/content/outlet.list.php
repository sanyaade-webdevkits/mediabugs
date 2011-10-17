	<tr>
		<td><a href="/outlets/edit?id=<?= $doc->id; ?>"><?= $doc->headline; ?></a></td>
		<td><?= $POD->pluralize($POD->getContents(array('type'=>'bug','bug_target'=>$doc->id))->totalCount(),'@number bug','@number bugs'); ?></td>	
		<td id="outlet_<?= $doc->id; ?>"><? if ($doc->status=='new') { ?><a href="#" onclick="return approveOutlet(<?= $doc->id; ?>);">Approve</a><? } else { ?>&radic;<? } ?></td>
	</tr>
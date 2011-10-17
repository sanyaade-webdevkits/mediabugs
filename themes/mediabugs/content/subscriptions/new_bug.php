<?= $doc->headline; ?> (<?= $doc->permalink ?>)
Status: <?= $doc->bug_status; ?> | Media Outlet: <? $outlet = $POD->getContent(array('id'=>$doc->bug_target)); echo $outlet->headline; ?> | Date Filed: <?= date('Y-m-d',strtotime($doc->date)); ?>


<?= $doc->body; ?>


==========================================


<?php $votes = $this->get_votes(); ?>
<p>Visitor Votes for Post:</p>
<div style='display:grid; grid-template-columns:1fr 1fr; grid-gap:5px;'>
    <span style='padding:5px 10px; border: 1px dotted;'>Positive: <?= $votes['yes'] ? $votes['yes'] : 0 ?></span>
    <span style='padding:5px 10px; border: 1px dotted;'>Negative: <?= $votes['no'] ? $votes['no'] : 0 ?></span>
</div>
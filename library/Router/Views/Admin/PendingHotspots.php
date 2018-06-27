<?php

if(isset($successMsg) && !is_null($successMsg))
    Util::createAlert("successMsg",$successMsg,ALERT_TYPE_SUCCESS,true);

if(isset($errorMsg) && !is_null($errorMsg))
    Util::createAlert("errorMsg",$errorMsg,ALERT_TYPE_DANGER,true);

function paginate($currentPage,$itemsPerPage,$total){
	$paginator = new JasonGrimes\Paginator($total,$itemsPerPage,$currentPage,"/admin/pendingSpots/(:num)");

	if ($paginator->getNumPages() > 1){ ?>
		<nav aria-label="Page navigation example"><ul class="pagination justify-content-center mt-3">
			<?php if ($paginator->getPrevUrl()){ ?>
				<li class="page-item"><a class="page-link" href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a></li>
				<?php } ?>
	
			<?php foreach ($paginator->getPages() as $page){ ?>
				<?php if ($page['url']){ ?>
					<li class="page-item<?php echo $page['isCurrent'] ? ' active' : ''; ?>">
						<a class="page-link" href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
					</li>
				<?php } else { ?>
					<li class="page-item disabled"><a class="page-link" href="#"><?php echo $page['num']; ?></a></li>
				<?php } ?>
			<?php } ?>
	
			<?php if ($paginator->getNextUrl()){ ?>
				<li class="page-item"><a class="page-link" href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a></li>
				<?php } ?>
		</ul></nav>
				<?php }
}

if(count($results) > 0){
    echo paginate($page,$itemsPerPage,$num);

    foreach($results as $hotspot){
        $creator = User::getUserById($hotspot->getCreator());

        ?>
<div class="card">
    <div class="card-body">
        <h5><?= $hotspot->getName(); ?></h5>
        <?= $hotspot->getAddress(); ?><br/>
        <?= $hotspot->getZipCode() . " " . $hotspot->getCity(); ?><br/>
        <div class="text-muted small mt-3">Eingereicht von <?= $creator != null ? $creator->getUsername() . " (" . $creator->getEmail() . ")" : " <i>N/A</i>"; ?></div>
    </div>

    <div class="card-footer">
        <form action="/admin/pendingSpots/<?= $page; ?>" method="post" class="float-left">
            <input type="hidden" name="hotspotId" value="<?= $hotspot->getId(); ?>"/>
            <input type="hidden" name="action" value="accept"/>
            <button type="submit" class="btn btn-success customBtn">Annehmen</button>
        </form>
        
        <form action="/admin/pendingSpots/<?= $page; ?>" method="post" class="ml-2 float-left">
            <input type="hidden" name="hotspotId" value="<?= $hotspot->getId(); ?>"/>
            <input type="hidden" name="action" value="decline"/>
            <button type="submit" class="btn btn-danger customBtn">Ablehnen</button>
        </form>

        <a href="/hotspot/<?= $hotspot->getId(); ?>" class="clearUnderline ml-2">
            <button type="button" class="btn btn-warning customBtn">Ansehen</button>
        </a>
    </div>
</div>
        <?php
    }

    echo paginate($page,$itemsPerPage,$num);
} else {
    Util::createAlert("noResults","Es konnten keine ausstehenden Hotspoteinreichungen gefunden werden.",ALERT_TYPE_DANGER);
}

?>
<?php

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

    foreach($results as $rating){
        $hotspot = $rating->getHotspot();
        if($hotspot == null) continue;

        ?>
        <div class="card bg-light mb-2">
            <div class="card-body">
                <div class="float-left">
                    <p class="mb-0">
                        <span class="font-weight-bold" style="font-size: large"><a href="<?= $app->routeUrl("/hotspot/" . $hotspot->getId()); ?>"><?= $hotspot->getName() . " (" . $hotspot->getCity() . ")"; ?></a></span><br/>
                        <?= $rating->getComment() != null ? $rating->getComment() . "<br/><br/>" : ""; ?>
                        <span class="text-dark small"><?= Util::timeago($rating->getTime()); ?></span>
                    </p>
                </div>
                <div class="starRatingReadOnly float-right" data-rating="<?= $rating->getStars(); ?>"></div>
            </div>
        </div>
        <?php
    }

    echo paginate($page,$itemsPerPage,$num);
} else {
    Util::createAlert("noResults","Du hast noch keine Hotspots bewertet.",ALERT_TYPE_DANGER);
}
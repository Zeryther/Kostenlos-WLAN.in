<div class="row">
	<div class="col-md-4">
		<div class="filterSettings">
			<div class="filterHeadline">Filtereinstellungen</div>

			<div class="px-3 my-2">
				<form action="<?= $app->routeUrl("/search"); ?>" method="get">
					<input type="hidden" name="q" value="<?= $query; ?>"/>

					<div class="form-group display-block">
						<label for="sorting">Sortierung</label>
						<select name="sorting" id="sorting" class="form-control">
							<option value="next"<?= $sorting == "next" ? " selected" : ""; ?>>Entfernung (Nächste zuerst)</option>
							<option value="last"<?= $sorting == "last" ? " selected" : ""; ?>>Entfernung (Weiteste zuerst)</option>
							<option value="newest"<?= $sorting == "newest" ? " selected" : ""; ?>>Alter (Neuste zuerst)</option>
							<option value="oldest"<?= $sorting == "oldest" ? " selected" : ""; ?>>Alter (Älteste zuerst)</option>
							<option value="best"<?= $sorting == "best" ? " selected" : ""; ?>>Bewertung (Beste zuerst)</option>
							<option value="worst"<?= $sorting == "worst" ? " selected" : ""; ?>>Bewertung (Schlechteste zuerst)</option>
						</select>
					</div>

					<div class="form-group display-block">
						<label for="distanceUnit">Distanzeinheit</label>
						<select name="distanceUnit" id="distanceUnit" class="form-control">
							<option value="km"<?= $useKilometers ? " selected" : ""; ?>>Kilometer (km)</option>
							<option value="mi"<?= $useKilometers ? "" : " selected"; ?>>Meilen (mi)</option>
						</select>
					</div>

					<div class="form-group display-block">
						<label for="maxDistance">Maximale Entfernung</label>
						
						<div class="input-group">
							<input name="maxDistance" id="maxDistance" type="number" class="form-control" value="<?= $maxDistance; ?>" min="<?= FILTER_MAX_DISTANCE_MINIMUM; ?>" max="<?= FILTER_MAX_DISTANCE_MAXIMUM; ?>"/>

							<div class="input-group-append">
								<span class="input-group-text" id="maxDistanceDisplay"><?= $useKilometers ? "km" : "mi"; ?></span>
							</div>
						</div>
					</div>

					<button type="submit" class="btn btn-warning btn-block customBtn">Anwenden</button>
				</form>
			</div>
		</div>

		<a href="<?= $app->routeUrl("/hotspot/add"); ?>" class="clearUnderline">
			<button type="button" class="my-2 btn btn-warning btn-block customBtn">Einen Hotspot einreichen</button>
		</a>

		<center class="my-3">
			<?php Util::renderAd(AD_TYPE_BLOCK); ?>
		</center>
	</div>

	<div class="col-md-8">
		<center class="mb-3">
			<?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
		</center>
		<?php

			if(count($hotspots) > 0){
				?>
		<div class="hotspotList">
			<?php

				foreach($hotspots as $data){
					$hotspot = $data[0];
					$distance = round($data[1],1);
					$top = false; // TODO
					?>
			<a href="<?= $app->routeUrl("/hotspot/" . $hotspot->getID()); ?>">
				<div class="hotspot">
					<?= $top ? '<div class="topIcon">TOP</div>' : ''; ?>
					<div class="address">
						<div class="name"><?= $hotspot->getName(); ?></div>
						<br/>
						<div class="sub"><?= $hotspot->getAddress(); ?>, <?= $hotspot->getZipCode(); ?> <?= $hotspot->getCity(); ?></div>
					</div>

					<div class="d-none d-xl-block info">Klicke für weitere Informationen</div>

					<div class="distance"><?= $distance; ?> <?= $useKilometers ? "km" : "mi"; ?> entfernt</div>
					<div class="starRatingReadOnlySmall float-right" data-rating="<?= $hotspot->getRating(); ?>"></div>
				</div>
			</a>
					<?php
				}

			?>
		</div>
				<?php
			} else {
				Util::createAlert("noHotspotsFound","Es konnten keine WLAN Hotspots in deiner Nähe gefunden werden.",ALERT_TYPE_DANGER);
			}

		?>
		<center class="mt-3">
			<?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
		</center>
	</div>
</div>
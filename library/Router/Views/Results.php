<div class="row">
	<div class="col-md-4">
		<div class="filterSettings">
			<div class="filterHeadline">Filtereinstellungen</div>

			<div class="px-3 my-2">
				<form action="<?= $app->routeUrl("/search"); ?>" method="get">
					<input type="hidden" name="q" value="<?= $query; ?>"/>

					<div class="form-group" style="display: block;">
						<label for="distanceUnit">Distanzeinheit</label>
						<select name="distanceUnit" id="distanceUnit" class="form-control">
							<option value="km"<?= $useKilometers ? " selected" : ""; ?>>Kilometer (km)</option>
							<option value="mi"<?= $useKilometers ? "" : " selected"; ?>>Meilen (mi)</option>
						</select>
					</div>

					<button type="submit" class="btn btn-warning btn-block customBtn">Anwenden</button>
				</form>
			</div>
		</div>

		<a href="" class="clearUnderline">
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
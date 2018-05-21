<div class="row">
	<div class="col-md-4">
		<div class="filterSettings">
			<div class="filterHeadline">Filtereinstellungen</div>

			asdasdas
		</div>

		<a href="" class="clearUnderline">
			<button type="button" class="my-2 btn btn-warning btn-block customBtn">Einen Hotspot einreichen</button>
		</a>
	</div>

	<div class="col-md-8">
		<?php

			if(count($hotspots) > 0){
				?>
		<div class="hotspotList">
			<?php

				foreach($hotspots as $data){
					$hotspot = $data[0];
					$distance = Util::formatNumber(round($data[1],2));
					$top = false;
					?>
			<a href="#">
				<div class="hotspot">
					<?= $top ? '<div class="topIcon">TOP</div>' : ''; ?>
					<div class="address">
						<div class="name"><?= $hotspot->getName(); ?></div>
						<br/>
						<div class="sub"><?= $hotspot->getAddress(); ?></div>
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
	</div>
</div>
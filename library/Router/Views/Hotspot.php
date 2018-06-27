<?php $photo = $hotspot->getPhotoURL(); ?><h1 class="display-4"><?= $hotspot->getName(); ?> <small class="text-muted"><?= $hotspot->getCity(); ?></small></h1>

<?php if(!$hotspot->isValid())
    Util::createAlert("invalidHotspot","Dieser Hotspot wurde von der Community hinzugefügt und noch nicht als gültig markiert. Diese Daten können eventuell inkorrekt sein.",ALERT_TYPE_DANGER); ?>

<center class="my-3">
    <?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
</center>

<div class="row">
    <?php if($photo != null){ ?>
    <div class="col-md-3">
        <img src="<?= $photo; ?>" style="width: 100%" class="rounded"/>
    </div>
    <?php } ?>
    <div class="col-md-<?= $photo != null ? "9" : "12"; ?>">
        <div class="card">
            <h5 class="card-header">Informationen</h5>

            <table class="table my-0">
                <tr>
                    <td style="width: 30%"><b>Name</b></td>
                    <td style="width: 70%"><?= $hotspot->getName(); ?></td>
                </tr>

                <tr>
                    <td style="width: 30%"><b>Adresse</b></td>
                    <td style="width: 70%"><?= $hotspot->getAddress(); ?><br/><?= $hotspot->getZipCode(); ?> <?= $hotspot->getCity(); ?></td>
                </tr>

                <tr>
                    <td style="width: 30%"><b>Hinzugefügt</b></td>
                    <td style="width: 70%"><?= Util::timeago($hotspot->getCreationTime()); ?></td>
                </tr>

                <tr>
                    <td style="width: 30%">&nbsp;</td>
                    <td style="width: 70%"><a class="btn btn-warning customBtn" href="https://www.google.com/maps/place/?q=<?= urlencode($hotspot->getAsGoogleQuery()); ?><?= $hotspot->getGooglePlaceId() != null ? ":" . $hotspot->getGooglePlaceId() : ""; ?>" target="_blank"><b>Auf Google Maps ansehen</b></a></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<center class="my-3">
	<?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
</center>

<div id="map" class="my-2"></div>


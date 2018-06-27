<?php $photo = $hotspot->getPhotoURL(); ?><h1 class="display-4"><?= $hotspot->getName(); ?> <small class="text-muted"><?= $hotspot->getCity(); ?></small></h1>

<?php if(!$hotspot->isValid())
    Util::createAlert("invalidHotspot","Dieser Hotspot wurde von der Community hinzugefügt und noch nicht als gültig markiert. Diese Daten können eventuell inkorrekt sein.",ALERT_TYPE_DANGER); ?>

<center class="my-3">
    <?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
</center>

<div class="row">
    <?php if($photo != null){ ?>
    <div class="col-md-3">
        <img src="<?= $photo; ?>" style="max-width: 100%" class="rounded text-center"/>
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

        <div id="map" class="my-2 rounded"></div>

        <div class="card" id="comments">
            <?php

                $ratings = [];

                $hotspotId = $hotspot->getId();
                $userId = Util::isLoggedIn() ? Util::getCurrentUser()->getId() : -1;

                $mysqli = Database::Instance()->get();
                $stmt = $mysqli->prepare("SELECT u.id AS userID,u.username,u.level AS userLevel,r.stars,r.comment,r.time FROM ratings AS r INNER JOIN users AS u ON r.user = u.id WHERE r.hotspot = ? AND u.id != ? ORDER BY r.user = ? DESC, r.comment IS NOT NULL, r.time DESC;");
                $stmt->bind_param("iii",$hotspotId,$userId,$userId);
                if($stmt->execute()){
                    $result = $stmt->get_result();

                    if($result->num_rows){
                        while($row = $result->fetch_assoc()){
                            array_push($ratings,[
                                "rating" => Rating::getRatingFromData($hotspot->getId(),$row["userID"],$row["stars"],$row["comment"],$row["time"]),
                                "username" => $row["username"],
                                "userID" => $row["userID"],
                                "userLevel" => $row["userLevel"]
                            ]);
                        }
                    }
                }
                $stmt->close();

            $ratingCount = count($ratings);
            
            if(Util::isLoggedIn() && Rating::getRating($hotspot->getId(),Util::getCurrentUser()->getId()) != null)
                $ratingCount++;

            ?><h5 class="card-header">Bewertungen (<?= $ratingCount; ?>)</h5>

            <div class="card-body">
                <?php if(Util::isLoggedIn()){
                    $successMsg = null;
                    $errorMsg = null;

                    if(isset($_POST["ratingComment"]) && isset($_POST["rating"])){
                        if(!empty($_POST["rating"]) && is_numeric($_POST["rating"]) && $_POST["rating"] >= 0){
                            $stars = (double)$_POST["rating"];
                            $comment = $_POST["ratingComment"];

                            if(empty($comment) || empty(trim($comment))) $comment = null;

                            $rating = Rating::getRating($hotspot->getId(),Util::getCurrentUser()->getId());

                            if($rating != null){
                                $rating->update($stars,$comment);
                                $successMsg = "Deine Bewertung wurde aktualisiert.";
                            } else {
                                $rating = Rating::createRating($hotspot->getId(),Util::getCurrentUser()->getId(),$stars,htmlspecialchars($comment));

                                if($rating != null){
                                    $successMsg = "Deine Bewertung wurde gespeichert.";
                                } else {
                                    $errorMsg = "Ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
                                }
                            }
                        } else {
                            $errorMsg = "Ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
                        }
                    }

                    if(!is_null($successMsg))
                        Util::createAlert("successMsg",$successMsg,ALERT_TYPE_SUCCESS);

                    if(!is_null($errorMsg))
                        Util::createAlert("errorMsg",$errorMsg,ALERT_TYPE_DANGER);

                    $displayPostData = !is_null($errorMsg);

                    if(!isset($rating) || is_null($rating))
                        $rating = Rating::getRating($hotspot->getId(),Util::getCurrentUser()->getId());

                    ?><form action="<?= $app->routeUrl("/hotspot/" . $hotspot->getId()); ?>#comments" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center mb-2">
                                <div class="starRating" id="ratingCommentStars"<?= $rating != null ? ' data-rating="' . $rating->getStars() . '"' : ""; ?>></div>
                            </div>

                            <textarea id="ratingComment" name="ratingComment" maxlength="2000" class="form-control" style="resize:none;"><?= $rating != null && $rating->getComment() != null ? $rating->getComment() : ""; ?></textarea>
                            <span id="ratingCommentCounter"></span>

                            <input type="hidden" name="rating" id="ratingValue" value="-1"/>

                            <button type="submit" class="btn btn-warning customBtn mt-2">Absenden</button>
                        </div>
                    </div>
                </form>
                <?php } else {
                    Util::createAlert("mustBeLoggedIn","Bitte logge dich ein um diesen Hotspot zu bewerten.",ALERT_TYPE_DANGER);
                } ?>
            </div>

            <?php

            if(count($ratings) > 0){
                ?>
            <hr class="my-0"/>

            <div class="card-body">
                <?php

                    foreach($ratings as $ratingData){
                        $rating = $ratingData["rating"];

                        ?>
                <div class="card bg-<?= $ratingData["userID"] == $userId ? "dark text-white" : ($ratingData["userLevel"] == "ADMIN" ? "success text-white" : "light"); ?> mb-2">
                    <div class="card-body">
                        <div class="float-left">
                            <p class="mb-0">
                                <span class="font-weight-bold" style="font-size: large"><?= $ratingData["username"]; ?></span><?= $ratingData["userLevel"] == "ADMIN" ? " &bull; Gigadrive" : ""; ?><br/>
                                <?= $rating->getComment() != null ? $rating->getComment() . "<br/><br/>" : ""; ?>
                                <span class="text-dark small"><?= Util::timeago($rating->getTime()); ?></span>
                            </p>
                        </div>
                        <div class="starRatingReadOnly float-right" data-rating="<?= $rating->getStars(); ?>"></div>
                    </div>
                </div>
                        <?php
                    }

                ?>
            </div>
                <?php
            }

            ?>
        </div>
    </div>
</div>

<center class="my-3">
	<?php Util::renderAd(AD_TYPE_LEADERBOARD); ?>
</center>

<?php

use KostenlosWLAN\Hotspot;
use KostenlosWLAN\Place;
use KostenlosWLAN\Util;

?>
<center class="mb-2"><?php Util::renderAd(AD_TYPE_LEADERBOARD); ?></center>
<?php

$successMsg = null;
$errorMsg = null;

if(isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["zipCode"]) && isset($_POST["city"])){
    if(!empty($_POST["name"]) && !empty($_POST["address"]) && !empty($_POST["zipCode"]) && !empty($_POST["city"])){
        $name = $_POST["name"];
        $address = $_POST["address"];
        $zipCode = $_POST["zipCode"];
        $city = $_POST["city"];

        if(strlen($name) <= 150){
            if(strlen($address) <= 175){
                if(strlen((String)$zipCode) == 5){
                    if(strlen($city) <= 150){
                        if(is_numeric($zipCode)){
                            $zipCode = (int)$zipCode;
                            $place = Place::getPlace($zipCode,$city);

                            if($place != null){
                                $query = $name . " " . $address . " " . $zipCode . " " . $city;

                                $googleData = Place::getGoogleGeocodeData($query);
                                if(isset($googleData["results"]) && is_array($googleData["results"]) && count($googleData["results"]) > 0){
                                    $doError = true;

                                    foreach($googleData["results"] as $result){
                                        if(isset($result["place_id"])){
                                            if((isset($result["types"]) && is_array($result["types"]) && (in_array("establishment",$result["types"]) || in_array("point_of_interest",$result["types"]))) || (isset($result["type"]) && is_string($result["type"]) && ($result["type"] == "establishment" || $result["type"] == "point_of_interest")) && (!isset($result["partial_match"]) || $result["partial_match"] != true)){
                                                if(isset($result["formatted_address"]) && strpos(strtolower($result["formatted_address"]),strtolower($zipCode . ", " . $city)) !== -1){
                                                    if(isset($result["geometry"])){
                                                        if(isset($result["geometry"]["location"])){
                                                            $googlePlaceId = $result["place_id"];
                                                            $street = explode(",",$result["formatted_address"])[0];
                                                            $zipCode = (int)explode(" ",trim(explode(",",$result["formatted_address"])[1]))[0];
                                                            $city = explode(" ",trim(explode(",",$result["formatted_address"])[1]))[1];
                                                            $latitude = $result["geometry"]["location"]["lat"];
                                                            $longitude = $result["geometry"]["location"]["lng"];
    
                                                            $doError = false;
                                
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if(!$doError){
                                        if(!Hotspot::isHotspotInDatabase($googlePlaceId,$street,$zipCode,$city)){
                                            $creator = Util::getCurrentUser()->getId();
                                            $mysqli = \Database::Instance()->get();

                                            $stmt = $mysqli->prepare("INSERT INTO `hotspots` (`name`,`address`,`zipCode`,`city`,`latitude`,`longitude`,`creator`,`valid`,`googlePlaceId`) VALUES(?,?,?,?,?,?,?,0,?);");
                                            $stmt->bind_param("ssisddis",$name,$address,$zipCode,$city,$latitude,$longitude,$creator,$googlePlaceId);
                                            if($stmt->execute()){
                                                $successMsg = "Vielen Dank für deine Einsendung. Dieser Hotspot ist nun in der Warteschlange und wird von unseren Mitarbeitern überprüft.";
                                            } else {
                                                $errorMsg = "Ein Fehler ist aufgetreten. " . $stmt->error;
                                            }
                                            $stmt->close();
                                        } else {
                                            $errorMsg = "Dieser Hotspot ist bereits in unserer Datenbank.";
                                        }
                                    } else {
                                        $errorMsg = "Dieser Hotspot konnte als gültig erkannt werden.";
                                    }
                                } else {
                                    $errorMsg = "Dieser Hotspot konnte als gültig erkannt werden.";
                                }
                            } else {
                                $errorMsg = "Dieser Ort konnte nicht gefunden werden.";
                            }
                        } else {
                            $errorMsg = "Die Postleitzahl muss numerisch sein.";
                        }
                    } else {
                        $errorMsg = "Die Stadt darf maximal 150 Zeichen lang sein.";
                    }
                } else {
                    $errorMsg = "Die Postleitzahl muss genau 5 Zeichen lang sein.";
                }
            } else {
                $errorMsg = "Die Adresse darf maximal 175 Zeichen lang sein.";
            }
        } else {
            $errorMsg = "Der Name darf maximal 150 Zeichen lang sein.";
        }
    } else {
        $errorMsg = "Bitte fülle alle Felder aus.";
    }
}

if(!is_null($successMsg))
    Util::createAlert("successMsg",$successMsg,ALERT_TYPE_SUCCESS);

if(!is_null($errorMsg))
    Util::createAlert("errorMsg",$errorMsg,ALERT_TYPE_DANGER);

if(is_null($successMsg)){
?><h1 class="text-center">Einen Hotspot einreichen</h1>

<div class="row">
    <div class="col-md-8 offset-2">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <form action="<?= $app->routeUrl("/hotspot/add"); ?>" method="post">
                    <fieldset>
                        <div class="form-group row">
                            <label for="name" class="control-label col-sm-2 col-form-label">Name</label>
                            
                            <div class="col-sm-10 mb-3">
                                <input class="form-control" type="text" name="name" id="name" placeholder="Café XY" autocomplete="off"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="control-label col-sm-2 col-form-label">Adresse</label>
                            
                            <div class="col-sm-10 mb-3">
                                <input class="form-control" type="text" name="address" id="address" placeholder="Musterstraße 12" autocomplete="off"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="zipCode" class="control-label col-sm-2 col-form-label">Postleitzahl</label>
                            
                            <div class="col-sm-10 mb-3">
                                <input class="form-control" type="text" name="zipCode" id="zipCode" placeholder="10119" autocomplete="off"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="control-label col-sm-2 col-form-label">Ort</label>
                            
                            <div class="col-sm-10 mb-3">
                                <input class="form-control" type="text" name="city" id="city" placeholder="Berlin" autocomplete="off"/>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block customBtn">Submit</button>
                        <button type="reset" class="btn btn-light btn-block customBtn">Cancel</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div><?php } ?><center class="my-2"><?php Util::renderAd(AD_TYPE_LEADERBOARD); ?></center>
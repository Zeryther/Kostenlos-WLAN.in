<?php

use KostenlosWLAN\Hotspot;
use KostenlosWLAN\Report;
use KostenlosWLAN\Util;

$successMsg = null;
$errorMsg = null;
$user = Util::getCurrentUser();

if(isset($_POST["reason"]) && isset($_POST["text"]) && isset($_POST["hotspot"])){
    $reason = $_POST["reason"];
    $text = $_POST["text"];

    if($reason == REPORT_REASON_BROKEN_HOTSPOT || $reason == REPORT_REASON_INVALID_DATA || $reason == REPORT_REASON_SPAM){
        if(empty($text) || empty(trim($text))){
            $text = null;
        } else {
            $text = Util::limitString($text,500);
        }

        if(is_numeric($_POST["hotspot"])){
            $hotspot = Hotspot::getHotspotById((int)$_POST["hotspot"]);

            if($hotspot != null){
                if(!$user->hasOpenReport($hotspot->getId())){
                    $report = Report::createReport($user->getId(),$hotspot->getId(),$reason,$text);

                    if($report != null){
                        $successMsg = "Dein Bericht wurde versandt und wird bald von einem Mitarbeiter bearbeitet.";
                    } else {
                        $errorMsg = "Ein Fehler ist aufgetreten." . ($user->getLevel() == ADMIN ? " (4)" : "");
                    }
                } else {
                    $errorMsg = "Du hast bereits ein Problem mit diesem Hotspot berichtet.";
                }
            } else {
                $errorMsg = "Ein Fehler ist aufgetreten." . ($user->getLevel() == ADMIN ? " (3)" : "");
            }
        } else {
            $errorMsg = "Ein Fehler ist aufgetreten." . ($user->getLevel() == ADMIN ? " (2)" : "");
        }
    } else {
        $errorMsg = "Ein Fehler ist aufgetreten." . ($user->getLevel() == ADMIN ? " (1)" : "");
    }
}

if(!is_null($successMsg))
    Util::createAlert("successMsg",$successMsg,ALERT_TYPE_SUCCESS);

if(!is_null($errorMsg))
    Util::createAlert("errorMsg",$errorMsg,ALERT_TYPE_DANGER);
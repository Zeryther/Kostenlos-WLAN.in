function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function saveDismiss(id){
	setCookie("registeredAlert"+id,"closed",10);
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function initMap(){
    let mapDiv = document.getElementById("map");

    if(mapDiv != null && typeof LATITUDE !== "undefined" && typeof LONGITUDE !== "undefined"){
        var map = new google.maps.Map(mapDiv, {
            zoom: 16,
            center: new google.maps.LatLng(LATITUDE,LONGITUDE),
            mapType: "normal"
        });
    
        var marker = new google.maps.Marker({position: new google.maps.LatLng(LATITUDE,LONGITUDE), map: map});
    
        var infoWindow = new google.maps.InfoWindow({
            content: MARKER_HTML
        });
    
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.open(map, marker);
        });
    } else {
        mapDiv = document.getElementById("homeMap");

        if(mapDiv != null){
            let url = "/api/spots";

            $.ajax({url: url}).done(function(result){
                var map = new google.maps.Map(mapDiv, {
                    zoom: 5,
                    center: new google.maps.LatLng(50.964409,11.051543),
                    mapType: "normal"
                });

                result.forEach(hotspot => {
                   let marker = new google.maps.Marker({
                       position: new google.maps.LatLng(hotspot.latitude,hotspot.longitude),
                       map: map
                   });

                   let infoWindow = new google.maps.InfoWindow({
                       content: '<b>' + hotspot.name + '</b><br/>' + hotspot.address + '<br/>' + hotspot.zipCode + ' ' + hotspot.city + '<br/><br/><a href="/hotspot/' + hotspot.id + '"><b>Mehr Informationen</b></a>'
                   });

                   google.maps.event.addListener(marker, 'click', function(){
                       infoWindow.open(map,marker);
                   });
                });
            }).fail(function(){
                mapDiv.html("Failed to load map.");
            })
        }
    }
}

$(document).ready(function(){
    $.timeago.settings.strings = {
        prefixAgo: "vor",
        prefixFromNow: "in",
        suffixAgo: "",
        suffixFromNow: "",
        seconds: "wenigen Sekunden",
        minute: "etwa einer Minute",
        minutes: "%d Minuten",
        hour: "etwa einer Stunde",
        hours: "%d Stunden",
        day: "etwa einem Tag",
        days: "%d Tagen",
        month: "etwa einem Monat",
        months: "%d Monaten",
        year: "etwa einem Jahr",
        years: "%d Jahren"
    };

    $("time.timeago").timeago();

    $("#distanceUnit").on("change", function(e){
        let maxDistanceDisplay = $("#maxDistanceDisplay");

        if(maxDistanceDisplay != null){
            let selected = this.options[e.target.selectedIndex].value;

            maxDistanceDisplay.html(selected);
        }
    });

    function updateRatingCommentCount(){
        let self = $("textarea#ratingComment");

        if(typeof self === "undefined" || self == null || self.length == 0) return;

        let length = self.val().length;
        let remaining = self.attr("maxlength")-length;
        
        let counter = $("#ratingCommentCounter");
        if(counter != null)
            counter.html(remaining.toString());
    }

    updateRatingCommentCount();

    $("textarea#ratingComment").keyup(function(){
        updateRatingCommentCount();
    });

    if($("#ratingCommentStars") != null){
        $("#ratingCommentStars").starRating({
            strokeColor: '#894A00',
            strokeWidth: 10,
            starSize: 30,
            disableAfterRate: false,
            starShape: "rounded",
            forceRoundUp: true,
            callback: function(currentRating, $el){ $("#ratingValue").val(currentRating); }
        });
    }

    if($(".starRatingReadOnly") != null){
        $(".starRatingReadOnly").starRating({
            strokeColor: '#894A00',
            strokeWidth: 10,
            starSize: 30,
            disableAfterRate: false,
            starShape: "rounded",
            forceRoundUp: true,
            readOnly: true
        });
    }

    if($(".starRatingReadOnlySmall") != null){
        $(".starRatingReadOnlySmall").starRating({
            strokeColor: '#894A00',
            strokeWidth: 10,
            starSize: 20,
            disableAfterRate: false,
            starShape: "rounded",
            forceRoundUp: true,
            readOnly: true
        });
    }
});
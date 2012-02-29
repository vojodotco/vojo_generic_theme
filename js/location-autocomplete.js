$(vojoCdhJuarez_InitLocationAutocomplete);

var _cdhAddressToLoc = {};

function vojoCdhJuarez_InitLocationAutocomplete() {
    $('#edit-locations-0-name').autocomplete({
        'minLength': 3,
        /**
         * Hit google to geocode the address the user inputs
         */
         'source': function(req, respcb) {
            var plc = req.term;
            var gc = new google.maps.Geocoder();
            var _cb = respcb;
            gc.geocode({'address': plc}, function(results) {
                var sugg = [];
                _cdhAddressToLoc = {};
                if(results && results.length) {
                    for(var i=0; i<results.length; i++) {
                        sugg.push(results[i].formatted_address);
                        _cdhAddressToLoc[results[i].formatted_address] = results[i].geometry.location;
                    }
                }
                return _cb(sugg);
            });
        },
        /**
         * When they select an item from autocomplete, update the map and hidden input fields
         */
        'select': function(event,ui) {
            var loc = _cdhAddressToLoc[ui.item.value];
            var normalLon = loc.lng();
            var normalLat = loc.lat();
            // now update the real fields
            $("#edit-locations-0-locpick-user-latitude").val(normalLat);
            $("#edit-locations-0-locpick-user-longitude").val(normalLon);
        }
    });
}

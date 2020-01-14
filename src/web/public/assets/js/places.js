(function() {
    var placesAutocomplete = places({
        appId: 'pl9PD12018HM',
        apiKey: '453998c55cdfd38bb7a2571688e88339',
        container: document.querySelector('#address')
    }).configure({
        language: 'fr',
        type: 'address',
        countries: 'fr,lu,be,ad,mc',
        hitsPerPage: 2,
        aroundLatLngViaIP: true
    });

    var $address = document.querySelector('#address-value')
    placesAutocomplete.on('change', function(e) {
        $address.textContent = e.suggestion.value
    });

    placesAutocomplete.on('clear', function() {
        $address.textContent = 'none';
    });

})();
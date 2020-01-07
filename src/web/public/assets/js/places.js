(function() {
    var placesAutocomplete = places({
        appId: 'pl9PD12018HM',
        apiKey: '453998c55cdfd38bb7a2571688e88339',
        container: document.querySelector('#address')
    }).configure({
        type: 'address'
    });

    var $address = document.querySelector('#address-value')
    placesAutocomplete.on('change', function(e) {
        $address.textContent = e.suggestion.value
    });

    placesAutocomplete.on('clear', function() {
        $address.textContent = 'none';
    });

})();
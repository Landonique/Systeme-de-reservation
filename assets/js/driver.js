const $ = require('jquery');
navigator.geolocation.getCurrentPosition(
    function success(position) {
        $("#voiture_latitude").val(position.coords.latitude)
        $("#voiture_longitude").val(position.coords.latitude)

    })
import {addSpinner, removeSpinner} from "./_loading";

require('../css/front.scss');

const $ = require('jquery');
const btnCarLocate = $('#btn-car-locate');
require('bootstrap');


$('.front-home').height($('body').height() - $('.navbar').outerHeight() - $('.jumbotron').outerHeight(true));
$(document).ready(function () {
    if (btnCarLocate.length) {
        btnCarLocate.click(function () {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function success(position) {
                        addSpinner(btnCarLocate, 'replace', 'fa-2x mb-2');
                        carLocation(position.coords.latitude, position.coords.longitude, function (return_data) {
                            removeSpinner(btnCarLocate);
                            const data = return_data.data;
                            $('#location-result .modal-body').html(`
                                <div class="card" style="width: 100%;">
                                    <img src="${data.image}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">${data.marque}</h5>
                                        <p class="card-text">
                                            ${data.matricule} <span class="text-muted">| ${data.nombrePlace} Places</span>
                                            <br>
                                            <span class="font-italic">Conduit par <span class="text-info ml-1"><i class="fa fa-user" style="font-size: .9rem"></i> ${data.user.lastname}</span></span>
                                        </p>
                                    </div>
                                </div>
                            `);
                            $('#location-result').modal('show');
                        });
                    },
                    function error() {
                        console.error('Une erreur s\'est produite lors de la récupération de l\'emplacement.');
                    });
            } else {
                console.log('La géolocalisation n\'est pas activée sur ce navigateur.');
            }
        });
    }

    function carLocation(lat, long, callback) {
        $.ajax({
            url: "/car/locate",
            type: "POST",
            data: {
                latitude: lat,
                longitude: long
            },
            async: true,
            success: function (return_data) {
                callback(return_data);
            }
        });
    }
});

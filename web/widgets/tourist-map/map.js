// https://github.com/googlemaps/js-marker-clusterer
$(function () {
    TouristObjectsMap.initMap();
});

var TouristObjectsMap = {
    map: null,
    searchBox: null,
    markerCluster: null,
    markers: {},
    infoWindow: null,
    initMap: function () {
        var mapContainer = $('.tourist-objects-map-container');
        var mapCanvas = mapContainer.find('.tourist-objects-map-canvas');
        const lat = mapCanvas.data('defaultLatitude'), long = mapCanvas.data('defaultLongitude'),
            zoom = mapCanvas.data('defaultZoom'), maxZoom = mapCanvas.data('maxZoom'), minZoom = mapCanvas.data('minZoom');
        TouristObjectsMap.map = new google.maps.Map(mapCanvas.get(0), {
            center: {lat: lat, lng: long},
            zoom: zoom,
            minZoom: minZoom,
            maxZoom: maxZoom,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            }
        });

        var input = mapContainer.find('.tourist-objects-map-search');
        input.show();
        TouristObjectsMap.searchBox = new google.maps.places.SearchBox(input.get(0));
        TouristObjectsMap.map.controls[google.maps.ControlPosition.TOP_CENTER].push(input.get(0));
        TouristObjectsMap.map.addListener('bounds_changed', function() {
            TouristObjectsMap.searchBox.setBounds(TouristObjectsMap.map.getBounds());
        });

        TouristObjectsMap.getCurrentLocation(function (position) {
            if (position) {
                TouristObjectsMap.map.setCenter(position);
            }
            TouristObjectsMap.addEventListeners();
        });
    },
    addEventListeners: function () {
        TouristObjectsMap.map.addListener('idle', function (e) {
            TouristObjectsMap.getPlaces(TouristObjectsMap.map.getBounds());
        });

        TouristObjectsMap.searchBox.addListener('places_changed', function() {
            var places = TouristObjectsMap.searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            TouristObjectsMap.map.fitBounds(bounds);
        });
    },
    getCurrentLocation: function (callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                callback({lat: position.coords.latitude, lng: position.coords.longitude});
            }, function () {
                console.error('The Geolocation service failed.');
                callback();
            });
        } else {
            console.warn('Error: Your browser doesn\'t support geolocation.');
            callback();
        }
    },
    getPlaces: function (bounds) {
        var data = {
            points: {
                a: {lat: bounds.getSouthWest().lat(), lng: bounds.getSouthWest().lng()},
                b: {lat: bounds.getNorthEast().lat(), lng: bounds.getNorthEast().lng()}
            }
        };
        $.ajax({
            url: $('.tourist-objects-map-canvas').data('placesUrl'),
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.status == 'success') {
                    TouristObjectsMap.setMarkersForPlaces(response.places);
                }
            }
        })
    },
    setMarkersForPlaces: function (places) {
        if (places) {
            if (TouristObjectsMap.markerCluster) {
                TouristObjectsMap.markerCluster.clearMarkers();
            }

            Object.keys(TouristObjectsMap.markers).forEach(function (key) {
                if (!(key in places)) {
                    var marker = TouristObjectsMap.markers[key];
                    marker.setMap(null);
                    marker = null;
                    delete TouristObjectsMap.markers[key];
                }
            });

            for (var key in places) {
                if (!(key in TouristObjectsMap.markers)) {
                    const place = places[key];
                    TouristObjectsMap.markers[place['id']] = new google.maps.Marker({
                        position: place['coordinates'],
                        map: TouristObjectsMap.map,
                        title: place['name'],
                        tourist_place: place
                    });
                    TouristObjectsMap.markers[place['id']].addListener('click', function () {
                        if (!TouristObjectsMap.infoWindow) {
                            TouristObjectsMap.infoWindow = new google.maps.InfoWindow();

                        }
                        TouristObjectsMap.infoWindow.setContent('Loading...');
                        $.ajax({
                            url: place['information_url'],
                            success: function (response) {
                                TouristObjectsMap.infoWindow.setContent(response);
                                // $('.tourist-object-information').html(response);
                                // $('.tourist-object-information').find('#gallery_2').attr('id', 'gallery_3');
                                // var selector = '#gallery_3 a';
                                // var options = {};
                                // $(document).off('click.gallery', selector).on('click.gallery', selector, function() {
                                //     console.log(123);
                                //     var links = $(this).parent().find('a.gallery-item');
                                //     options.index = $(this)[0];
                                //     console.log($(this)[0])
                                //     console.log(links)
                                //     blueimp.Gallery(links, options);
                                //     return false;
                                // });
                                // // $('.gallery-item').on('click', function (e) {
                                // //     e.preventDefault();
                                // //     console.log(window.blueimp.Gallery);
                                // // })
                            }
                        });
                        TouristObjectsMap.infoWindow.open(TouristObjectsMap.map, TouristObjectsMap.markers[place['id']]);
                    });
                }
            }
            TouristObjectsMap.markerCluster = new MarkerClusterer(TouristObjectsMap.map, Object.values(TouristObjectsMap.markers), {
                imagePath: 'media/tourist-map/cluster-images/m',
                maxZoom: 18
            });
        }
    }
};
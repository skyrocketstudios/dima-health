(function($) {
    "use strict";

    var maps = {};
    qode.modules.maps = maps;
    qode.modules.maps.qodeInitMultipleListingMap = qodeInitMultipleListingMap;
    qode.modules.maps.qodeInitSingleListingMap = qodeInitSingleListingMap;
    qode.modules.maps.qodeInitMobileMap = qodeInitMobileMap;
    qode.modules.maps.qodeGoogleMaps = {};

    $(document).ready(qodeOnDocumentReady);
    $(window).load(qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);
    $(window).scroll(qodeOnWindowScroll);

    function qodeOnDocumentReady() {}

    function qodeOnWindowLoad() {
        qodeInitSingleListingMap();
        qodeInitMultipleListingMap();
        qodeInitMobileMap();
    }

    function qodeOnWindowResize() {}

    function qodeOnWindowScroll() {}

    function qodeInitSingleListingMap() {
        var mapHolder = $('#qode-ls-single-map-holder');
        if ( mapHolder.length ) {
            qode.modules.maps.qodeGoogleMaps.getDirectoryItemAddress({
                mapHolder: 'qode-ls-single-map-holder'
            });
        }
    }

    function qodeInitMultipleListingMap() {
        var mapHolder = $('#qode-ls-multiple-map-holder');
        if ( mapHolder.length ) {
            qode.modules.maps.qodeGoogleMaps.getDirectoryItemsAddresses({
                mapHolder: 'qode-ls-multiple-map-holder',
                hasFilter: true
            });
        }
    }

    qode.modules.maps.qodeGoogleMaps = {

        //Object varibles
        mapHolder : {},
        map : {},
        markers : {},
        radius : {},

        /**
         * Returns map with single address
         *
         * @param options
         */
        getDirectoryItemAddress : function( options ) {
            /**
             * use qodeMapsVars to get variables for address, latitude, longitude by default
             */
            var defaults = {
                location : qodeSingleMapVars.single['currentListing'].location,
                type : qodeSingleMapVars.single['currentListing'].listingType,
                zoom : 16,
                mapHolder : '',
                draggable : qodeMapsVars.global.draggable,
                mapTypeControl : qodeMapsVars.global.mapTypeControl,
                scrollwheel : qodeMapsVars.global.scrollable,
                streetViewControl : qodeMapsVars.global.streetViewControl,
                zoomControl : qodeMapsVars.global.zoomControl,
                title : qodeSingleMapVars.single['currentListing'].title,
                content : '',
                styles: qodeMapsVars.global.mapStyle,
                markerPin : qodeSingleMapVars.single['currentListing'].markerPin,
                featuredImage : qodeSingleMapVars.single['currentListing'].featuredImage,
                itemUrl : qodeSingleMapVars.single['currentListing'].itemUrl
            };
            var settings = $.extend( {}, defaults, options );

            //Save variables for later usage
            this.mapHolder = settings.mapHolder;

            //Get map holder
            var mapHolder = document.getElementById( settings.mapHolder );

            //Initialize map
            var map = new google.maps.Map( mapHolder, {
                zoom : settings.zoom,
                draggable : settings.draggable,
                mapTypeControl : settings.mapTypeControl,
                scrollwheel : settings.scrollwheel,
                streetViewControl : settings.streetViewControl,
                zoomControl : settings.zoomControl
            });

            //Set map style
            map.setOptions({
                styles: settings.styles
            });

            //Try to locate by latitude and longitude
            if ( typeof settings.location !== 'undefined' && settings.location !== null) {
                var latLong = {
                    lat : parseFloat(settings.location.latitude),
                    lng : parseFloat(settings.location.longitude)
                };
                //Set map center to location
                map.setCenter(latLong);
                //Add marker to map

                var templateData = {
                    title : settings.title,
                    address : settings.location.address,
                    featuredImage : settings.featuredImage,
                    itemUrl : settings.itemUrl
                };

                var customMarker = new CustomMarker({
                    map : map,
                    position : latLong,
                    templateData : templateData,
                    markerPin : settings.markerPin
                });

                this.initMarkerInfo();

            }

        },

        /**
         * Returns map with multiple addresses
         *
         * @param options
         */
        getDirectoryItemsAddresses : function( options ) {
            var defaults = {
                geolocation : false,
                mapHolder : 'qode-ls-multiple-map-holder',
                addresses : qodeMultipleMapVars.multiple.addresses,
                draggable : qodeMapsVars.global.draggable,
                mapTypeControl : qodeMapsVars.global.mapTypeControl,
                scrollwheel : qodeMapsVars.global.scrollable,
                streetViewControl : qodeMapsVars.global.streetViewControl,
                zoomControl : qodeMapsVars.global.zoomControl,
                zoom : 16,
                styles: qodeMapsVars.global.mapStyle,
                radius : 50, //radius for marker visibility, in km
                hasFilter : false
            };
            var settings = $.extend({}, defaults, options );

            //Get map holder
            var mapHolder = document.getElementById( settings.mapHolder );

            //Initialize map
            var map = new google.maps.Map( mapHolder, {
                zoom : settings.zoom,
                draggable : settings.draggable,
                mapTypeControl : settings.mapTypeControl,
                scrollwheel : settings.scrollwheel,
                streetViewControl : settings.streetViewControl,
                zoomControl : settings.zoomControl
            });

            //Save variables for later usage
            this.mapHolder = settings.mapHolder;
            this.map = map;
            this.radius = settings.radius;

            //Set map style
            map.setOptions({
                styles: settings.styles
            });

            //If geolocation enabled set map center to user location
            if ( navigator.geolocation && settings.geolocation ) {
                this.centerOnCurrentLocation();
            }

            //Filter addresses, remove items without latitude and longitude
            var addresses = [],
                addressesLength = settings.addresses.length;
            if(settings.addresses.length !== null){
                for ( var i = 0; i < addressesLength; i++ ) {
                    var location = settings.addresses[i].location;
                    if ( typeof location !== 'undefined' && location !== null ) {

                        if ( location.latitude !== '' && location.longitude !== '' ) {
                            addresses.push(settings.addresses[i]);
                        }
                    }
                }
            }


            //Center map and set borders of map
            this.setMapBounds( addresses );

            //Add markers to the map
            this.addMultipleMarkers( addresses );

        },

        /**
         * Add multiple markers to map
         */
        addMultipleMarkers : function( markersData ) {

            var map = this.map;

            var markers = [];
            //Loop through markers
            var len = markersData.length;
            for ( var i = 0; i < len; i++ ) {

                var latLng = {
                    lat: parseFloat(markersData[i].location.latitude),
                    lng: parseFloat(markersData[i].location.longitude)
                };

                //Custom html markers
                //Insert marker data into info window template
                var templateData = {
                    title : markersData[i].title,
                    address : markersData[i].location.address,
                    featuredImage : markersData[i].featuredImage,
                    itemUrl : markersData[i].itemUrl
                };

                var customMarker = new CustomMarker({
                    position : latLng,
                    map : map,
                    templateData : templateData,
                    markerPin : markersData[i].markerPin
                });

                markers.push(customMarker);

            }

            this.markers = markers;

            //Init map clusters ( Grouping map markers at small zoom values )
            this.initMapClusters();

            //Init marker info
            this.initMarkerInfo();

            //Init visible circle area around center of map
            var that = this;
            google.maps.event.addListener(map, 'idle', function(){
                var visibleRadius = new google.maps.Circle({
                    strokeColor: '#FF0000',
                    strokeOpacity: 0,
                    strokeWeight: 0,
                    fillColor: '#FF0000',
                    fillOpacity: 0,
                    map: map,
                    center: map.getCenter(),
                    radius: that.radius * 1000 //in meters
                });
                //Display only markers in circle
                //that.refreshCircleAreaMarkers( visibleRadius.getBounds() );
            });

        },

        /**
         * Set map bounds for Map with multiple markers
         *
         * @param addressesArray
         */
        setMapBounds : function( addressesArray ) {

            var bounds = new google.maps.LatLngBounds();
            for ( var i = 0; i < addressesArray.length; i++ ) {
                bounds.extend( new google.maps.LatLng( parseFloat(addressesArray[i].location.latitude), parseFloat(addressesArray[i].location.longitude) ) );
            }

            this.map.fitBounds( bounds );

        },

        /**
         * Init map clusters for grouping markers on small zoom values
         */
        initMapClusters : function() {

            //Activate clustering on multiple markers
            var markerClusteringOptions = {
                minimumClusterSize: 2,
                maxZoom: 12,
                styles : [{
                    width: 50,
                    height: 60,
                    url: '',
                    textSize: 12
                }]
            };
            var markerClusterer = new MarkerClusterer(this.map, this.markers, markerClusteringOptions);

        },

        initMarkerInfo : function() {

            $(document).on('click', '.qode-map-marker', function() {
                var self = $(this),
                    markerHolders = $('.qode-map-marker-holder'),
                    infoWindows = $('.qode-info-window'),
                    markerHolder = self.parent('.qode-map-marker-holder'),
                    infoWindow = self.siblings('.qode-info-window');

                if ( markerHolder.hasClass('active') ) {
                    markerHolder.removeClass( 'active' );
                    infoWindow.fadeOut(0);
                } else {
                    markerHolders.removeClass('active');
                    infoWindows.fadeOut(0);
                    markerHolder.addClass('active');
                    infoWindow.fadeIn(300);
                }

            });

        },
        /**
         * Info Window for displaying data on map markers
         *
         * @returns {google.maps.InfoWindow}
         */
        addInfoWindow : function() {

            var contentString = '';
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            return infoWindow;

        },

        /**
         * If geolocation enabled center map on users current position
         */
        centerOnCurrentLocation : function() {
            var map = this.map;
            navigator.geolocation.getCurrentPosition(
                function(position){
                    var center = {
                        lat : position.coords.latitude,
                        lng : position.coords.longitude
                    };
                    map.setCenter(center);
                }
            );
        },

        /**
         * Refresh area for visible markers
         *
         * @param circleArea
         */
        refreshCircleAreaMarkers : function( circleArea ) {

            var length = this.markers.length;
            for ( var i = 0; i < length; i++ ) {
                if ( circleArea.contains( this.markers[i].getPosition() ) ) {
                    this.markers[i].setVisible(true);
                } else {
                    this.markers[i].setVisible(false);
                }
            }

        },

    };

    function qodeInitMobileMap() {

        var mapOpener = $('.qode-listing-view-larger-map a'),
            mapOpenerIcon = mapOpener.children('i'),
            mapHolder = $('.qode-map-holder');
        if (mapOpener.length) {
            mapOpener.click(function(e){
                e.preventDefault();
                if (mapHolder.hasClass('qode-fullscreen-map')) {
                    mapHolder.removeClass('qode-fullscreen-map');
                    mapOpenerIcon.removeClass('icon-basic-magnifier-minus');
                    mapOpenerIcon.addClass('icon-basic-magnifier-plus');
                } else {
                    mapHolder.addClass('qode-fullscreen-map');
                    mapOpenerIcon.removeClass('icon-basic-magnifier-plus');
                    mapOpenerIcon.addClass('icon-basic-magnifier-minus');
                }
                qode.modules.maps.qodeGoogleMaps.getDirectoryItemsAddresses();
            });
        }
    }

})(jQuery);
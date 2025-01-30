document.addEventListener('alpine:init', () => {
    console.log('Alpine initialized'); // Debugging

    Alpine.data('filamentGeolocateMe', () => ({
        loading: false,
        error: null,

        async getLocation() {
            console.log('Getting location...'); // Debugging
            if (!navigator.geolocation) {
                this.error = 'Geolocation is not supported by your browser.';
                console.error(this.error); // Debugging
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0,
                    });
                });

                const location = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                };

                console.log('Location retrieved:', location); // Debugging
                this.$wire.$parent.setGeolocationData(location);
            } catch (error) {
                this.error = this.getErrorMessage(error);
                console.error('Geolocation error:', error); // Debugging
            } finally {
                this.loading = false;
            }
        },

        getErrorMessage(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    return 'Permission denied. Please enable location access in your browser settings.';
                case error.POSITION_UNAVAILABLE:
                    return 'Location information is unavailable.';
                case error.TIMEOUT:
                    return 'The request to get location timed out.';
                default:
                    return 'Failed to retrieve your location.';
            }
        },

        init() {
            console.log('Alpine component initialized'); // Debugging

            this.$wire.on('getGeolocation', () => {
                console.log('getGeolocation event received'); // Debugging
                this.getLocation();
            });
        },
    }));
});

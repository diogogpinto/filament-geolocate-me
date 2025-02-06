export default function geolocateMe() {
    return {
        init() {
            Livewire.on('getLocationFromAlpine', () => {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        Livewire.dispatch('geolocationFromAlpine', {
                            data: {
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                                accuracy: position.coords.accuracy
                            }
                        });
                    },
                    (error) => {
                        Livewire.dispatch('geolocationFromAlpine', {
                            data: {
                                error: error.message
                            }
                        });
                    }
                );
            });
        }
    }
}

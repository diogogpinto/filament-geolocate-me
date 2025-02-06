export default function geolocateMe() {
    return {
        init() {
            Livewire.on('getLocationFromAlpine', () => {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        Livewire.dispatch('geolocationSuccess', {
                            data: {
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                                accuracy: position.coords.accuracy
                            }
                        });
                    },
                    (error) => {
                        Livewire.dispatch('geolocationError', { message: error.message });
                    }
                );
            });
        }
    }
}

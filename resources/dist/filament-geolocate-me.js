export default function geolocateMe() {
    return {
        init() {
            Livewire.on('getLocationFromAlpine', () => {
                console.log('start');
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        Livewire.dispatch('geolocationFromAlpine', {
                            coordinatesData: {
                                // latitude: position.coords.latitude,
                                // longitude: position.coords.longitude,
                                latitude: 48.858093,
                                longitude: 2.294694,
                                accuracy: position.coords.accuracy,
                            },
                        })
                    },
                    (error) => {
                        Livewire.dispatch('geolocationFromAlpine', {
                            coordinatesData: {
                                error: error.message,
                            },
                        })
                    },
                )
            })
        },
    }
}

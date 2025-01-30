export default function filamentGeolocateMe() {
    return {
        getLocation() {  // New method to be triggered by button
            if (!navigator.geolocation) {
                this.handleError('Geolocation is not supported by your browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.handleSuccess(position.coords);
                },
                (error) => {
                    this.handleError(error.message);
                }
            );
        },
        handleSuccess(coords) {
            Livewire.dispatch('geolocationSuccess', { coords });
        },
        handleError(message) {
            Livewire.dispatch('geolocationError', { message });
        }
    };
}

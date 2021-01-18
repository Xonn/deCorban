// Create an instance of the Stripe object with your publishable API key
const stripe = Stripe('pk_test_51HMWueE4KkenaKplcHUoTaUisWuuv2M3A9PNTP1CszwyOzzuYSVnemsqXFobodlWUOYOSxCsYYYq6n5sxZUBnr2t00y0rUPEXz');
let checkoutButton = $('.checkout-button');

checkoutButton.on('click', function () {
    let galeryId = $(this).data('galery-id');
    let type = $(this).data('type');

    fetch(Routing.generate('stripe_checkout', {'galery': galeryId, 'type': type}), {
            method: "POST",
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (session) {
            return stripe.redirectToCheckout({
                sessionId: session.id
            });
        })
        .then(function (result) {
            // If redirectToCheckout fails due to a browser or network
            // error, you should display the localized error message to your
            // customer using error.message.
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function (error) {
            console.error("Error:", error);
        }    
    );
});
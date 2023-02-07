
const stripe = Stripe(stripePublicKey);

const elements = stripe.elements();

const style = {
  base: {
    color: "#0570de",
    fontFamily: 'Arial, sans-serif',
    fontSmoothing: "antialiased",
    fontSize: "16px",
    "::placeholder": {
    }
  },
  invalid: {
     fontFamily:'Arial, sans-serif',
     color: "#fa755a",
  }
};
console.log("coucous payment");
const card = elements.create("card" , { style: style });
// stripe injects an iframe into the Dom
console.log(card);
card.mount("#card-element");
console.log(card.mount);
card.on("change", function (event) { // disable the Pay button if there are no card details in the Element
document.querySelector("button").disabled = event.empty;
document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
});

const form = document.getElementById("payment-form");

form.addEventListener("submit", function (event) {
    event.preventDefault();
   // Complete payment when the submit button is clicked
   stripe.confirmCardPayment(clientSecret, {
      payment_method: {
      card: card
      }
      }).then(function (result) {
      if (result.error) { // show error to customer
         console.log(result.error.message);
      } else { // the payment succeeded
         // on fait une redirection
         window.location.href = redirectAfterSuccesUrl;
      }
    });
  });
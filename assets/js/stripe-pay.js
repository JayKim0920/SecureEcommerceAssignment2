// stripe-pay.js
const stripe = Stripe("pk_test_51RunwxRvWYtj9TKmOuOKy928uWQcYYdO0pWbO2haEpvh0FOzZu4XnmIzV6GUEo5vnTe5DQsz12NQIbmRfNR03Pkw00bhlFHgcG"); // <-- Stripe Public key goes here

document.getElementById("stripe-button").addEventListener("click", function () {
  fetch("/create-checkout-session.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      // Can send order info here
      line_items: [
        {
          price_data: {
            currency: "usd",
            product_data: { name: "Electric Bike Model 1" },
            unit_amount: 129900
          },
          quantity: 1
        }
      ],
      // Success/Cancel page
      success_url: window.location.origin + "/stripeSuccess.html",
      cancel_url: window.location.origin + "/stripeCancel.html"
    })
  })
  .then(res => {
    if (!res.ok) throw new Error("Network response not ok");
    return res.json();
  })
  .then(session => {
    if(session.id) {
      return stripe.redirectToCheckout({ sessionId: session.id });
    } else {
      throw new Error("No session id returned");
    }
  })
  .then(result => {
    if (result && result.error) {
      alert(result.error.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert("Cannot proceed payment. Check the console.");
  });
});

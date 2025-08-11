function onGooglePayLoaded() {
    const paymentsClient = new google.payments.api.PaymentsClient({ environment: 'TEST' });

    const isReadyToPayRequest = {
        apiVersion: 2,
        apiVersionMinor: 0,
        allowedPaymentMethods: [{
            type: 'CARD',
            parameters: {
                allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
                allowedCardNetworks: ['MASTERCARD', 'VISA']
            }
        }]
    };

    paymentsClient.isReadyToPay(isReadyToPayRequest)
        .then(function(response) {
            if (response.result) {
                const button = paymentsClient.createButton({
                    onClick: onGooglePayButtonClicked,
                    buttonColor: 'default',
                    buttonType: 'buy'
                });
                document.getElementById('container-google-pay').appendChild(button);
            } else {
                console.warn("Google Pay not available");
            }
        })
        .catch(function(err) {
            console.error("isReadyToPay Error:", err);
        });
}

function onGooglePayButtonClicked() {
    const paymentDataRequest = getGooglePaymentDataRequest();
    const paymentsClient = new google.payments.api.PaymentsClient({ environment: 'TEST' });

    paymentsClient.loadPaymentData(paymentDataRequest)
        .then(function (paymentData) {
            console.log("Google Pay Success:", paymentData);
            alert("Payment Successful");
        })
        .catch(function (err) {
            console.error("Google Pay Error:", err);
            alert("Error");
        });
}

function getGooglePaymentDataRequest() {
    return {
        apiVersion: 2,
        apiVersionMinor: 0,
        allowedPaymentMethods: [{
            type: 'CARD',
            parameters: {
                allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
                allowedCardNetworks: ['MASTERCARD', 'VISA']
            },
            tokenizationSpecification: {
                type: 'PAYMENT_GATEWAY',
                parameters: {
                    gateway: 'example',
                    gatewayMerchantId: 'exampleGatewayMerchantId'
                }
            }
        }],
        merchantInfo: {
            merchantId: '01234567890123456789',
            merchantName: "Alice's Bike Shop"
        },
        transactionInfo: {
            totalPriceStatus: 'FINAL',
            totalPrice: '1499.00',
            currencyCode: 'USD',
            countryCode: 'US'
        }
    };
}

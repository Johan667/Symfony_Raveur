let stripe = Stripe('sk_test_51L6amqAgDjI611jf49n3RURuEVn6KbawPxt0CKby4wsENM9plWmKeqkq7Cm3Sl1W4JcvjewbvVCBrwyA5knu6b2500QdV5lalL')
let elements = stripe.elements()

let card = elements.create("card")
card.mount("#card-element")

card.addEventListener("change", (event) => {
    let displayError = document.getElementById("card-errors")
    if(event.error){
        displayError.textContent = event.error.message;
    }else{
        displayError.textContent = "";
    }
})

var form = document.getElementById('payment-form')
form.addEventListener('submit', function(event) {
    event.preventDefault()
    stripe.createToken(card).then(function(result) {
        if(result.error) {
            var errorElement = document.getElementById('card-errors')
            errorElement.textContent = result.error.message
        } else {
            stripeTokenHandler(result.token)
        }
    })
})

function stripeTokenHandler(token) {
    var form = document.getElementById('payment-form')
    var hiddenInput = document.createElement('input')
    hiddenInput.setAttribute('type', 'hidden')
    hiddenInput.setAttribute('name', 'stripeToken')
    hiddenInput.setAttribute('value', token.id)
    form.appendChild(hiddenInput)
    form.submit()
}
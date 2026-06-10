document.addEventListener('DOMContentLoaded', function () {

    const pricePerNight = parseFloat(document.getElementById('price-per-night').value);
    const maxGuests     = parseInt(document.getElementById('max-guests').value);

    const checkIn  = document.getElementById('check-in');
    const checkOut = document.getElementById('check-out');
    const guests   = document.getElementById('num-guests');

    const numNightsDisplay  = document.getElementById('num-nights-display');
    const totalPriceDisplay = document.getElementById('total-price-display');

    const numNightsInput  = document.getElementById('num-nights');
    const totalPriceInput = document.getElementById('total-price');

    const bookBtn     = document.getElementById('book-btn');
    const dateError   = document.getElementById('date-error');
    const guestsError = document.getElementById('guests-error');

    const today    = new Date();
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);

    checkIn.min  = today.toISOString().split('T')[0];
    checkOut.min = tomorrow.toISOString().split('T')[0];

    function calculateAndDisplay() {
        if (!checkIn.value || !checkOut.value) {
            numNightsDisplay.textContent  = '';
            totalPriceDisplay.textContent = '';
            numNightsInput.value          = '';
            totalPriceInput.value         = '';
            return;
        }

        const inDate  = new Date(checkIn.value);
        const outDate = new Date(checkOut.value);
        const nights  = Math.round((outDate - inDate) / 86400000);

        if (nights < 1) {
            dateError.textContent         = 'Check-out date must be after check-in date';
            numNightsDisplay.textContent  = '';
            totalPriceDisplay.textContent = '';
            numNightsInput.value          = '';
            totalPriceInput.value         = '';
            bookBtn.disabled              = true;
            return;
        }

        dateError.textContent = '';

        const total          = nights * pricePerNight;
        const formattedTotal = 'KES ' + total.toLocaleString('en-KE', { maximumFractionDigits: 0 });

        numNightsDisplay.textContent  = nights + (nights === 1 ? ' night' : ' nights');
        totalPriceDisplay.textContent = formattedTotal;
        numNightsInput.value          = nights;
        totalPriceInput.value         = total.toFixed(2);

        if (validateGuests()) {
            bookBtn.disabled = false;
        }
    }

    function validateGuests() {
        const numGuests = parseInt(guests.value);

        if (numGuests > maxGuests) {
            guestsError.textContent = 'Maximum ' + maxGuests + ' guests allowed for this room';
            bookBtn.disabled        = true;
            return false;
        }

        guestsError.textContent = '';
        return true;
    }

    checkIn.addEventListener('change', calculateAndDisplay);
    checkIn.addEventListener('input',  calculateAndDisplay);
    checkOut.addEventListener('change', calculateAndDisplay);
    checkOut.addEventListener('input',  calculateAndDisplay);

    guests.addEventListener('input', function () {
        validateGuests();
        if (checkIn.value && checkOut.value) {
            calculateAndDisplay();
        }
    });

});
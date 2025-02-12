function getTrackingNumber() {
    const trackingNumber = document.getElementById('tracking-number').value;

    if (!trackingNumber) {
        alert('Prašome įvesti siuntos numerį.');
        return;
    }

    console.log("Siunčiame siuntos numerį:", trackingNumber);

    fetch('trackPackage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'trackingNumber=' + encodeURIComponent(trackingNumber)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(
                `Siuntos informacija:\n` +
                `Vardas: ${data.name}\n` +
                `Pavardė: ${data.surname}\n` +
                `El. paštas: ${data.email}\n` +
                `Telefono numeris: ${data.phone}\n` +
                `Gatvė: ${data.street}\n` +
                `Sukurta: ${data.createdAt}`
            );
        } else {
            alert(data.message || "Siunta nerasta.");
        }
    })
    .catch(error => {
        console.error("Klaida siunčiant užklausą:", error);
        alert("Įvyko klaida užklausoje. Bandykite dar kartą.");
    });
}

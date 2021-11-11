<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder; ?>
<script>
    var button = document.getElementById("los");
    button.addEventListener("click", ermittlePosition);
    var ausgabe = document.getElementById("ausgabe");

    function ermittlePosition() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(zeigePosition);
        } else {
            ausgabe.innerHTML = "Ihr Browser unterstützt keine Geolocation.";
        }
    }

    function zeigePosition(position) {
        ausgabe.innerHTML = "Ihre Koordinaten sind:<br> Breite: " + position.coords.latitude + "<br>Länge: " + position.coords.longitude;
    }
</script>

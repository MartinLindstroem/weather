<?php

namespace Anax\View;

?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
crossorigin=""></script>

<h1>Väder</h1>
<p>Ange en geografisk position eller en giltig ip-adress för att se vädret för den platsen. Den geografiska positionen anges som {latitud,longitud} till exempel <code>56.16,15.59</code></p>

<form method="get">
    <input type="text" name="location">
    <input type="submit" value="Sök">
</form>

<?php if ($isValid || $validLocation) : ?> 
    <p>Latitud: <?= $locationInfo["lat"] ?? $lat ?></p>
    <p>Longitud: <?= $locationInfo["lon"] ?? $lon ?></p>
    <p>Stad: <?= $locationInfo["address"]["city"] ?? null ?></p>
    <p>Kommun: <?= $locationInfo["address"]["municipality"] ?? null ?></p>
    <p>Län: <?= $locationInfo["address"]["county"] ?? null ?></p>
    <p>Postkod: <?= $locationInfo["address"]["postcode"] ?? null ?></p>
    <p>Land: <?= $locationInfo["address"]["country"] ?? null ?></p>
<?php else : ?>
    <?php if ($location) :?>
        <p>Kunde inte hitta positionen, var snäll och ange en giltig position.</p>
    <?php endif; ?>
<?php endif; ?>
<br>
<br>
<div class="weather-container">
<?php if (array_key_exists("daily", $weatherResult)) : ?>
<table class="weather-forecast-table">
    <caption><b>Weather Forecast</b></caption>
    <tr>
        <th>Date</th>
        <th>Low</th>
        <th>High</th>
        <th>Weather</th>
    </tr>
    <?php foreach ($weatherResult["daily"] as $row) : ?>
    <tr>
        <td><b><?= date("l", $row["dt"])?></b> <br> <?= date("j F", $row["dt"]) ?></td>
        <td><?= round($row["temp"]["min"], 0) ?>&#176;</td>
        <td><?= round($row["temp"]["max"], 0) ?>&#176;</td>
        <td><?= $row["weather"][0]["main"] ?> <br> <img src="http://openweathermap.org/img/wn/<?= $row["weather"][0]["icon"] ?>@2x.png" ></td>
    </tr>
    <?php endforeach ?>
</table>
<?php endif; ?>
<br>
<br>
<?php if (array_key_exists("current", $weatherHistoryResult[0])) : ?>
<table class="weather-history-table">
    <caption><b>Weather History (5 previous days)</b></caption>
    <tr>
        <th>Date</th>
        <th>Temperature</th>
        <th>Weather</th>
    </tr>
    
    <?php foreach ($weatherHistoryResult as $row) : ?>
    <tr>
        <td><b><?= date("l", $row["current"]["dt"])?></b> <br> <?= date("j F", $row["current"]["dt"]) ?></td>
        <td><?= round($row["current"]["temp"], 0) ?>&#176;</td>
        <td><?= $row["current"]["weather"][0]["main"] ?> <br> <img src="http://openweathermap.org/img/wn/<?= $row["current"]["weather"][0]["icon"] ?>@2x.png" ></td>
    </tr>
    <?php endforeach ?>
</table>
<?php endif; ?>
</div>
<br>
<div id="mapid" style="height: 680px;"></div>

<script type="text/javascript">
    var ipResult = <?php echo json_encode($ipResult); ?>;
    var locationResult = <?php echo json_encode($weatherResult); ?>;
</script>
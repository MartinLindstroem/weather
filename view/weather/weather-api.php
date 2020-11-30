<h1>Weather REST API</h1>

<h4>Weather</h4>
<p>To see the weather for a certain location send a POST request with either latitude and longitude or a valid ip-address (<code>location: latitude,longitude</code>) or (<code>location: ip-address</code>) in the form-data in the body.</p>
<p>Example:</p>
<code>POST <?=$url?></code> <br>
<p>body: <code>location: 56.16,15.59</code> <br></p>

<p>Result: </p>

<pre style="background-color: #eee; color: black;">
{
    "latitude": "56.16",
    "longitude": "15.59",
    "city": "Karlskrona",
    "municipality": "Karlskrona kommun",
    "county": "Blekinge l\u00e4n",
    "postcode": "37132",
    "country": "Sverige",
    "weather": [
        {
            "date": "2020-11-23",
            "min": 6,
            "max": 9,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-24",
            "min": 7,
            "max": 9,
            "weather": "Rain"
        },
        {
            "date": "2020-11-25",
            "min": 8,
            "max": 9,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-26",
            "min": 5,
            "max": 8,
            "weather": "Rain"
        },
        {
            "date": "2020-11-27",
            "min": 2,
            "max": 5,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-28",
            "min": 2,
            "max": 5,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-29",
            "min": 2,
            "max": 4,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-30",
            "min": 3,
            "max": 4,
            "weather": "Clouds"
        }
    ],
    "weather_history": [
        {
            "date": "2020-11-22",
            "temp": 8,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-21",
            "temp": 8,
            "weather": "Rain"
        },
        {
            "date": "2020-11-20",
            "temp": 1,
            "weather": "Clear"
        },
        {
            "date": "2020-11-19",
            "temp": 3,
            "weather": "Clouds"
        },
        {
            "date": "2020-11-18",
            "temp": 10,
            "weather": "Clouds"
        }
    ]
}
</pre>


<br>
<h4>Try it out</h4>
<form method="post">
    <input type="text" name="location" placeholder="Enter location">
    <input type="submit" name="submit" value="Validate">
</form>


<h1>Planets</h1>

<p>The planets in order from the sun.</p>

<h2>Primary Planets</h2>

<div class="planets">
<?=$lmdpages::renderData('data/planets-data.json', 'planet')?>
</div>

<h2>Dwarf Planets</h2>

<p>Dwarf planets include Ceres (which sits between Mars and Jupiter), Pluto (see below), Makemake, Haumea, and Eris.</p>

<div class="planets">
<?=$lmdpages::renderBlock('planet', [
    "title" => "Pluto",
    "caption" => "Pluto as seen from the New Horizons spacecraft in 2015 at a distance of 476,000 miles",
    "image" => "planet-9-pluto.jpg",
    "blurb" => "<p>Ipsum nonumy et sed sed erat erat et magna magna dolor. Lorem dolor dolor duo sit eos vero, invidunt nonumy dolor et magna, sed et consetetur est est amet, sea gubergren consetetur rebum et eirmod invidunt justo voluptua duo, stet sit sed diam vero, labore et dolor no at, et lorem justo lorem aliquyam. Ea magna justo rebum clita. Sadipscing.</p>"
])?>
</div>
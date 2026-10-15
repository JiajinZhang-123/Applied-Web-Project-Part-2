<?php
$pageTitle = 'Finova Technologies | Job Search';
$mainLabel = 'Search Jobs';

include __DIR__ . '/header.inc';
include __DIR__ . '/nav.inc';
?>

<h1>Search Jobs</h1>
<p>Search for available jobs at Finova Technologies.</p>

<form action="search.php" method="get">
    <label for="keyword">Search by job title:</label>
    <input type="text"
           id="keyword"
           name="keyword"
           placeholder="e.g Data Analyst">

    <button type="submit">Search</button>

</form>
<?php
include __DIR__ . '/footer.inc';
?>


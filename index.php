<?php 
    $url = 'https://experts.colorado.edu/es/fis/_search';
    
    // request options
    $searchParams = [
        'index' => 'fis',
        'query' => [
            'match' => [
                'familyName' => 'gasiewski'
            ]
        ]
    ];
    $query = '?' . http_build_query($searchParams);
    $fullUrl = $url . $query;
    
    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_HTTPHEADER, 'Content-Type: application/json');
    curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($searchParams));
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_VERBOSE, true);

    $response = curl_exec($request);
    $reqStatus = curl_getinfo($request);
    curl_close($request);

    $parsedResponse = json_decode($response);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASIC HTTP</title>
</head>
<body>
    <p>A request to: <?php echo $url 
    . '<br/>with search params: ' . json_encode($searchParams);
    ?><p>
    <ol>
        <?php foreach ($parsedResponse->hits->hits as $index => $hit) { ?>
            <li><h3><?php echo $hit->_source->name; ?></h3> 
                <ul><li><?php echo $hit->_source->researchOverview; ?></li></ul>
            </li>
         <?php } ?>
    </ol>
    <div style="color: red"><?php echo $response ?></div>

</body>
</html>

<!-- curl -X POST "localhost:9200/twitter/_doc?routing=kimchy&pretty" -H 'Content-Type: application/json' -d'
{
    "user" : "kimchy",
    "post_date" : "2009-11-15T14:12:12",
    "message" : "trying out Elasticsearch"
} -->

<!-- curl -X GET "localhost:9200/_search?pretty" -H 'Content-Type: application/json' -d'
{
    "query" : {
        "term" : { "user" : "kimchy" }
    }
}
' -->

<?php 
    $url = 'https://aggregator-data.artic.edu/api/v1/search';
    
    // request options
    $searchParams = [
        'resources' => 'artworks',
        'fields' => [
            'id',
            'title',
            'artist_title',
            'image_id',
            'date_display',
        ],
        'limit' => 5,
        'query' => [
            'function_score' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            [ 'term' => [ 'is_public_domain' => true ]],
                            [ 'exists' => [ 'field' => 'image_id' ]],
                            [ 'exists' => [ 'field' => 'thumbnail.width' ]],
                            [ 'exists' => [ 'field' => 'thumbnail.height' ]],
                        ],
                    ],
                ],
                'random_score' => [
                    'field' => 'id',
                ]
            ],
        ]
    ];

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($searchParams));
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($request, CURLOPT_VERBOSE, true);

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
    <title>ES - Art</title>
</head>
<body>
    <?php if (isset($parsedResponse->data)): ?>

        <p>A request to: <?php echo $url; ?><p>
        <ol>
        
            <?php foreach ($parsedResponse->data as $artwork): ?>

                <li><ul>
                    <?php foreach ($artwork as $key => $value): ?>

                        <li>
                            <?php 
                                echo $key . ':        ';
                                echo gettype($value) === "string" ? $value : json_encode($value);
                            ?>
                        </li>

                    <?php endforeach; ?>
                </ul></li><br/>
            <?php endforeach; ?>

        </ol>
    <?php else: ?>

        <div style="color: red">
            <?php 
                echo var_dump($parsedResponse); 
                echo var_dump($reqStatus);
                echo json_encode($searchParams, JSON_PRETTY_PRINT);
            ?>
        </div>

    <?php endif; ?>
    

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

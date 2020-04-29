<?php 
    $url = 'https://experts.colorado.edu/es/fispubs-v1/_search';
    
    // request options
    $searchParams = [
        'q' => 'citedAuthors:(Kim AND Massey) AND publicationDate:[2007 TO 2020]'
    ];
    
    $query = http_build_query($searchParams);
    $fullUrl = $url . "?" . $query;

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $fullUrl);
    curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

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
    <title>ES - Publications</title>
</head>
<body>
    <?php if (isset($parsedResponse->hits->hits)): ?>

        <div style="color: green">
            <?php 
                foreach ($parsedResponse->hits->hits as $hit) {
                    echo '<div style="border-bottom: 2px solid green;">';
                    echo '<p>' . $hit->_source->citedAuthors . '</p>';
                    echo '<p>' . $hit->_source->publicationDate . '</p>';
                    echo '</div>'; 
                }
            ?>
        </div>

    <?php else: ?>

        <div style="color: blue">
            <?php 
                echo var_dump($parsedResponse); 
                echo var_dump($reqStatus);
                echo json_encode($searchParams, JSON_PRETTY_PRINT);
                echo '<br/>' . $fullUrl;
            ?>
        </div>

    <?php endif; ?>
    

</body>
</html>
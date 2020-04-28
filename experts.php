<?php 
    $url = 'https://experts.colorado.edu/es/fis/_search';
    
    // request options
    $searchParams = [
        'q' => 'givenName:mark OR researchArea:"Environmental Sciences"'
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
    <title>ES - Experts</title>
</head>
<body>
    <?php if (isset($parsedResponse->hits->hits)): ?>

        <p>A request to: <?php echo $fullUrl; ?><p>
        <ol>

            <?php foreach ($parsedResponse->hits->hits as $person): ?>
                <li><?php echo isset($person->_source->thumbnail) ? '<img src="' . $person->_source->thumbnail . '"/>' : '<p>No Picture Available</p>'; ?> 
                    <ul>
                        <li style="list-style: none">Name: <?php echo isset($person->_source->name) ? $person->_source->name : 'No Name Available'; ?></li>
                        <li style="list-style: none">Email: <?php echo isset($person->_source->email) ? $person->_source->email : 'No Email Available'; ?></li>
                        <li style="list-style: none">Website: <?php echo isset($person->_source->website) ? $person->_source->website : 'No Website Available'; ?></li>
                    </ul>
                </li>
            <?php endforeach; ?>

        </ol>

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
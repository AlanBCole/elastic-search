<?php 
    $url = 'https://experts.colorado.edu/es/fis/_search';
    
    // request options
    $searchParams = [
        'query' => [
            'bool' => [
                'must' => [
                    ['term' => ['email' => 'mark.hernandez@Colorado.EDU']]
                ],
            ],
        ],
    ];

    $query = http_build_query($searchParams);
    $fullUrl = $url . "?" . $query;

    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $fullUrl);
    curl_setopt($request, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($searchParams));
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
    <title>ES - Experts</title>
</head>
<body>
    <?php if (isset($parsedResponse->aksdfasdjkf)): ?>

        <p>A request to: <?php echo $url; ?><p>
        <ol>

            <?php foreach ($parsedResponse->data as $artwork): ?>
                <li><h3><?php echo $artwork->artist_title ? $artwork->artist_title : 'Anonymous'; ?></h3> 
                    <ul>
                        <li style="list-style: none">Title: <?php echo $artwork->title; ?></li>
                        <li style="list-style: none">Date: <?php echo $artwork->date_display; ?></li>
                        <li style="list-style: none">ID: <?php echo $artwork->id; ?></li>
                        <li style="list-style: none">Image ID: <?php echo $artwork->image_id; ?></li>
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
            ?>
        </div>

    <?php endif; ?>
    

</body>
</html>
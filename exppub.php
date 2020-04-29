<?php 
    
    // request options

    $pub = make_request( 
        'https://experts.colorado.edu/es/', 'fispubs-v1',
        []
    );



    function make_request($url, $index = '_all', $searchParams) {
        $query = '?' . http_build_query($searchParams);
        $fullUrl = $url . '/' . $index . '/_search' . $query;

        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, $fullUrl);
        curl_setopt($req, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($req);
        $reqStatus = curl_getinfo($req);
        curl_close($req);

        return ['response' => json_decode($response), 'status' => $reqStatus, 'request' => $fullUrl];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ES - ExpPub</title>
</head>
<body>
    <?php if (isset($pub['response']->hits->hits)): ?>

        <div style="color: green">
            <?php 
                foreach ($pub['response']->hits->hits as $hit) {
                    $names = [];
                    foreach ($hit->_source->authors as $author) {
                        $names[] = '<a href="' . $author->uri . '">' . $author->name . '</a>';
                    }
                    echo '<div style="border-bottom: 2px solid green;">';
                    if (isset($hit->_source->doi)) {
                        echo '<h4><a href="' . 'https://dx.dor.org/' . $hit->_source->doi . '">' . $hit->_source->name . '</a></h4>';
                    }
                    else {
                        echo '<h4>' . $hit->_source->name . '</h4>';
                    }
                    echo '<p><strong>Authors: </strong>' . join($names, '; ') . '</p>';
                    if (!empty($hit->_source->publishedIn)) {
                        echo '<p><strong>Published in: </strong>' . join($names, '; ') . '</p>';
                    }
                    echo '<p><strong>Publication Date: </strong>' . $hit->_source->publicationDate . '</p>';
                    echo '<p><strong>Type: </strong>' . $hit->_source->mostSpecificType . '</p>';
                    echo '</div>'; 
                }
            ?>
        </div>

    <?php else: ?>

        <div style="color: red">
            <?php 
                echo var_dump($pub['request']); 
                echo var_dump($pub['status']);
            ?>
        </div>

    <?php endif; ?>
    

</body>
</html>
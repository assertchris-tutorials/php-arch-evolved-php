<?php

// ...get the client data

$clients = json_decode(
    file_get_contents(__DIR__ . "/clients.json")
);

// ...get all of the clients that must be billed

$billable = [];

foreach ($clients as $client) {
    if ($client->deceased !== true && $client->balance < 0) {
        array_push($billable, $client);
    }
}

// ...get only the fields we want to send in email

$mapped = [];
$excludedFields = ["deceased"];

// $mapped = array_map(function ($client) use ($excludedFields) {
    // foreach ($excludedFields as $field) {
    //     unset($client->$field);
    // }
    
    // return $client;
// }, $billable);

foreach ($billable as $client) {
    $clone = clone $client;

    foreach ($excludedFields as $field) {
        unset($clone->$field);
    }
    
    array_push($mapped, $clone);
}

// ...send emails to everyone in $mapped

return [$billable, $mapped];

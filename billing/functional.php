<?php

// ...get the client data

$clients = json_decode(
    file_get_contents(__DIR__ . "/clients.json")
);

// ...get all of the clients that must be billed

$billable = array_filter($clients, function ($client) {
    return $client->deceased !== true && $client->balance < 0;
});

// ...get only the fields we want to send in email

$excludedFields = ["deceased"];

$mapped = array_map(function ($client) use ($excludedFields) {
    $clone = clone $client;

    foreach ($excludedFields as $field) {
        unset($clone->$field);
    }
    
    return $clone;
}, $billable);

// ...send emails to everyone in $mapped

return [$billable, $mapped];

<?php
return [
  "storage" => [
    "chunks" => "chunks",
    "disk" => "local"
  ],
  "clear" => [
    "timestamp" => "-3 HOURS",
    "schedule" => [
        "enabled" => true,
        "cron" => "25 * * * *"
    ]
  ],
  "chunk" => [
    "name" => [
      "use" => [
        "session" => true,
        "browser" => false
      ]
    ]
  ]
];

<?php

return [

  "default" => "Local",

  "base_url"=> [
      "Local" => ['uploaded','$id'],
  ],

  "config" => [
      "validations" => [
          "min"   => true,
          "max"   => true,
          "type"  => -1,
      ],
      "max_size" => 111111111111110,
      "min_size" => 0,
      "allowed_types" => [
        'image/jpeg'
      ],
      "not_allowed_types" => [

      ]
  ],

  "disks" => [

      "Local" => [
          "folder" => realpath(DISKS_PATH . 'local'),
          "visibility" => 1,
          "db" => [
              "db_name" => "Luna_db",
              "db_user" => "root",
              "db_pass" => "admin0147",
              "table"   => "local_storage"
          ]
      ],

      "FTP" => [

      ],

      "SFTP" => [

      ]

  ]
];
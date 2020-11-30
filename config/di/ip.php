<?php
/**
 * Configuration file for database service.
 */
return [
    // Services to add to the container.
    "services" => [
        "ip" => [
            "active" => true,
            "shared" => true,
            "callback" => function () {
                $ip = new \Marty\Weather\IpModel();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("private_keys");

                // Set the api key configuration
                $key = $config["config"]["ipStack"] ?? "";
                $ip->setApiKey($key);

                return $ip;
            }
        ],
    ],
];

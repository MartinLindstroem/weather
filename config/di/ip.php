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

                if (file_exists("config/private_keys.php")) {
                    $config = $cfg->load("private_keys");

                    // Set the api key configuration
                    $key = $config["config"]["ipStack"] ?? "";
                    $ip->setApiKey($key);
                } else {
                    $key = getenv("IPSTACK_KEY") ?? "";
                    $ip->setApiKey($key);
                }
                return $ip;
            }
        ],
    ],
];

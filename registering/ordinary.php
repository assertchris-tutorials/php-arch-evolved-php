<?php

namespace HelpfulRobot\Action\User;

use function HelpfulRobot\Crypto\hash;
use function HelpfulRobot\Database\prepare;

class RegisterAction
{
    public function run($parameters): \Amp\Promise
    {
        return \Amp\call(function () {
            $inserted = yield $this->insertUser([
                "email" => $parameters->email,
                "password" => $parameters->password,
            ]);

            $fetched = yield $this->fetchUser([
                "id" => $inserted->insertId,
            ]);

            return $fetched;
        });
    }

    private function insertUser($values): \Amp\Promise
    {
        return \Amp\call(function () {
            $values->salt = bin2hex(random_bytes(32));
            $values->password = hash($values->password . $values->salt);

            $query = "INSERT INTO users ...";
            $result = yield prepare($query, $values);

            return $result;
        });
    }

    private function fetchUser($values): \Amp\Promise
    {
        return \Amp\call(function () {
            $query = "SELECT * FROM users WHERE ...";
            $result = yield prepare($query, $values);

            $row = yield $result->fetchObject();

            return $row;
        });
    }
}

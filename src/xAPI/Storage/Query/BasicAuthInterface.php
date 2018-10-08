<?php

/*
 * This file is part of lxHive LRS - http://lxhive.org/
 *
 * Copyright (C) 2017 G3 International
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with lxHive. If not, see <http://www.gnu.org/licenses/>.
 *
 * For authorship information, please view the AUTHORS
 * file that was distributed with this source code.
 */

namespace API\Storage\Query;

interface BasicAuthInterface extends QueryInterface
{

    /**
     * Find record by Mongo ObjectId
     * @param string $name
     * @param string $description
     * @param int $expiresAt unix timestamp
     * @param object $user user storage record
     * @param array[string] $permissions
     * @param string $key token key
     * @param string $secret token secret
     *
     * @return \API\DocumentInterface
     */
    public function storeToken($name, $description, $expiresAt, $user, $permissions, $key = null, $secret = null);

    /**
     * Find record by token key and token secret
     * @param string $key token key
     * @param string $secret token secret
     *
     * @return \API\DocumentInterface
     */
    public function getToken($key, $secret);

    /**
     * Delete record by token key
     * @param string $key token key
     *
     * @return \API\Storage\Query\API\DeletionResult
     */
    public function deleteToken($key);

    /**
     * Expire record by token key
     * @param string $key token key
     *
     * @return \MongoDB\Driver\Cursor
     */
    public function expireToken($key);

    /**
     * Find all records
     *
     * @return \API\DocumentInterface
     */
    public function getTokens();
}

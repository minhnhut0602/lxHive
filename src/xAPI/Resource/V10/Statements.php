<?php

/*
 * This file is part of lxHive LRS - http://lxhive.org/
 *
 * Copyright (C) 2017 Brightcookie Pty Ltd
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

namespace API\Resource\V10;

use API\Resource;
use API\Service\Statement as StatementService;
use API\Validator\V10\Statement as StatementValidator;
use API\View\V10\Statements as StatementView;

class Statements extends Resource
{
    /**
     * @var \API\Service\Statement
     */
    private $statementService;

    /**
     * @var \API\Validator\Statement
     */
    private $statementValidator;

    /**
     * Get statement service.
     */
    public function init()
    {
        $this->statementService = new StatementService($this->getContainer());
        $this->statementValidator = new StatementValidator($this->getContainer());
    }

    /**
     * Handle the Statement GET request.
     */
    public function get()
    {
        // Check authentication
        //$this->getContainer()->auth->checkPermission('statements/read');

        // Do the validation
        $this->statementValidator->validateRequest();
        $this->statementValidator->validateGetRequest();

        // Load the statements
        $statementResult = $this->statementService->statementGet();

        // Render them
        $view = new StatementView($this->getResponse(), $this->getDiContainer());

        if ($statementResult->getSingleStatementRequest()) {
            $view = $view->renderGetSingle($statementResult);
        } else {
            $view = $view->renderGet($statementResult);
        }

        return $this->jsonResponse(Resource::STATUS_OK, $view);
    }

    public function put()
    {
        // Check authentication
        //$this->getContainer()->auth->checkPermission('statements/write');

        $request = $this->getContainer()['parser']->getData();
        // Do the validation
        $this->statementValidator->validateRequest();
        $this->statementValidator->validatePutRequest();

        // Save the statements
        $this->statementService->statementPut();

        // Always an empty response, unless there was an Exception
        return $this->response(Resource::STATUS_NO_CONTENT);
    }

    public function post()
    {
        // Check authentication
        //$this->getContainer()->auth->checkPermission('statements/write');

        // Do the validation and multipart splitting
        $this->statementValidator->validateRequest();
        $this->statementValidator->validatePostRequest();

        // Save the statements
        $statementResult = $this->statementService->statementPost();

        $view = new StatementView($this->getResponse(), $this->getDiContainer());
        $view = $view->renderPost($statementResult);

        return $this->jsonResponse(Resource::STATUS_OK, $view);
    }

    public function options()
    {
        //Handle options request
        $this->setResponse($this->getResponse()->withHeader('Allow', 'POST,PUT,GET,DELETE'));
        return $this->response(Resource::STATUS_OK);
    }

    /**
     * @return \API\Service\Statement
     */
    public function getStatementService()
    {
        return $this->statementService;
    }

    /**
     * @return \API\Validator\Statement
     */
    public function getStatementValidator()
    {
        return $this->statementValidator;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Extracts JSON request from multipart/mixed request.
     *
     * @param \Slim\Http\Request $request Request object
     *
     * @return \Slim\Http\Request Request object
     */
    protected function extractJsonRequestFromMultipart($request)
    {
        $jsonRequest = $request->parts()->get(0);

        return $jsonRequest;
    }

    /**
     * Extracts all attachment requests from main request.
     *
     * @param \Slim\Http\Request $request Whole request
     *
     * @return array Array of \Slim\Http\Request's that represent attachments
     */
    protected function extractAttachmentsFromRequest($request)
    {
        $requests = $request->parts()->all();
        array_shift($requests);

        return $requests;
    }
}

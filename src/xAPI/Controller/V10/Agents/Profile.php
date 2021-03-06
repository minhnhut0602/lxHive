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

namespace API\Controller\V10\Agents;

use API\Controller;
use API\Service\AgentProfile as AgentProfileService;
use API\View\V10\AgentProfile as AgentProfileView;

class Profile extends Controller
{
    /**
     * @var \API\Service\AgentProfile
     */
    private $agentProfileService;

    /**
     * Get agent profile service.
     */
    public function init()
    {
        $this->agentProfileService = new AgentProfileService($this->getContainer());
    }

    /**
     * Handle the Statement GET request.
     */
    public function get()
    {
        // Check authentication
        $this->getContainer()->get('auth')->requirePermission('profile');

        // TODO 0.11.x request validation

        $documentResult = $this->agentProfileService->agentProfileGet();

        // Render them
        $view = new AgentProfileView($this->getResponse(), $this->getContainer());

        if ($documentResult->getIsSingle()) {
            $view = $view->renderGetSingle($documentResult);
            return $this->response(Controller::STATUS_OK, $view);
        } else {
            $view = $view->renderGet($documentResult);
            return $this->jsonResponse(Controller::STATUS_OK, $view);
        }
    }

    public function put()
    {
        // Check authentication
        $this->getContainer()->get('auth')->requirePermission('profile');

        // TODO 0.11.x request validation

        // Save the profiles
        $documentResult = $this->agentProfileService->agentProfilePut();

        //Always an empty response, unless there was an Exception
        return $this->response(Controller::STATUS_NO_CONTENT);
    }

    public function post()
    {
        // Check authentication
        $this->getContainer()->get('auth')->requirePermission('profile');

        // TODO 0.11.x request validation

        // Save the profiles
        $documentResult = $this->agentProfileService->agentProfilePost();

        //Always an empty response, unless there was an Exception
        return $this->response(Controller::STATUS_NO_CONTENT);
    }

    public function delete()
    {
        // Check authentication
        $this->getContainer()->get('auth')->requirePermission('profile');

        // TODO 0.11.x request validation

        // Delete the profiles
        $deletionResult = $this->agentProfileService->agentProfileDelete();

        //Always an empty response, unless there was an Exception
        return $this->response(Controller::STATUS_NO_CONTENT);
    }

    public function options()
    {
        //Handle options request
        $this->setResponse($this->getResponse()->withHeader('Allow', 'POST,PUT,GET,DELETE'));
        return $this->response(Controller::STATUS_OK);
    }

    /**
     * Gets the value of agentProfileService.
     *
     * @return \API\Service\AgentProfile
     */
    public function getAgentProfileService()
    {
        return $this->agentProfileService;
    }
}

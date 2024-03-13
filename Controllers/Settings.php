<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
use Leantime\Domain\Wiki\Controllers\Templates;
use Leantime\Plugins\DefaultTicketTemplate\Repository\DefaultTicketTemplateRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Settings Controller for DefaultTicketTemplate plugin
 *
 * @package    leantime
 * @subpackage plugins
 */
class Settings extends Controller
{
    public const NO_DEFAULT = 'No default';

    /**
     * Get method.
     *
     * @return Response
     */
    public function get(): Response
    {
        $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
        $projects = $defaultTicketTemplateRepository->getAllAvailableProjects();

        $templateController = app()->make(Templates::class);
        $templates = json_decode($templateController->get([])->getContent(), true);

        // Remove unnecessary data, only 'title' is used later.
        $templates = array_map(function ($template) {
            unset($template['description']);
            unset($template['content']);
            unset($template['category']);
            return $template;
        }, $templates);

        $this->tpl->assign('projects', $projects);
        $this->tpl->assign('templates', $templates);
        $this->tpl->assign('default', self::NO_DEFAULT);

        return $this->tpl->display('defaultTicketTemplate.settings');
    }

    /**
     * Post method.
     *
     * @param array $params
     *
     * @return RedirectResponse
     */
    public function post(array $params): RedirectResponse
    {
        $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
        $projects = $defaultTicketTemplateRepository->getAllAvailableProjects();

        // We should receive a param per project.
        // Also note that the default 'max_input_vars' is 1000,
        // hence this will fail if more than 1000 projects exists.
        if (count($projects) != count($params)) {
            $this->tpl->setNotification('Failed saving settings.', 'error');
        } else {
            // Do the updating if change detected.
            foreach ($projects as $project) {
                $projectId = $project['projectId'];
                $compareValue = $params[$projectId] === self::NO_DEFAULT ? null : $params[$projectId];
                if ($project['templateName'] != $compareValue) {
                    $defaultTicketTemplateRepository->handleTemplateProjectRelation($compareValue, $projectId);
                }
            }

            $this->tpl->setNotification('The settings were successfully saved.', 'success');
        }

        return Frontcontroller::redirect(BASE_URL . '/DefaultTicketTemplate/settings');
    }
}

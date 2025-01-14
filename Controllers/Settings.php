<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller\Controller;
use Leantime\Core\Controller\Frontcontroller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\TicketTemplate\Repository\TicketTemplateRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Settings Controller for TicketTemplate plugin
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
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        $defaultTicketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $projects = $defaultTicketTemplateRepository->getAllAvailableProjects();

        $templates = $defaultTicketTemplateRepository->getAllAvailableTemplates();

        $this->tpl->assign('projects', $projects);
        $this->tpl->assign('templates', $templates);
        $this->tpl->assign('noDefault', self::NO_DEFAULT);

        return $this->tpl->display('ticketTemplate.settings');
    }

    /**
     * Post method.
     *
     * @param array<string, mixed> $params
     *
     * @return RedirectResponse
     */
    public function post(array $params): RedirectResponse
    {
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $projects = $ticketTemplateRepository->getAllAvailableProjects();
        $postedProjects = $params['projects'];

        // We should receive a param per project.
        // Also note that the default 'max_input_vars' is 1000,
        // hence this will fail if more than 1000 projects exists.
        if (count($projects) != count($postedProjects)) {

            $this->tpl->setNotification('Failed saving settings', 'error');
        } else {
            // Do the updating if change detected.
            foreach ($projects as $project) {
                $projectId = $project['projectId'];
                $compareValue = $postedProjects[$projectId] === self::NO_DEFAULT ? null : $postedProjects[$projectId];
                if ($project['templateId'] != $compareValue) {
                    $ticketTemplateRepository->handleTemplateProjectRelation($compareValue, $projectId);
                }
            }

            $this->tpl->setNotification('Settings were successfully saved', 'success');
        }

        return Frontcontroller::redirect(BASE_URL . '/TicketTemplate/settings');
    }
}

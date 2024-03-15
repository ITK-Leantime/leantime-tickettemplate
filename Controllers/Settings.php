<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
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
    public const NO_DEFAULT_TRANSLATION_KEY = 'tickettemplate.settings.no_default';

    /**
     * Get method.
     *
     * @return Response
     */
    public function get(): Response
    {
        // Currently, translations are read before the plugin register is handled,
        // resulting in plugin translations not being considered,
        // without an extra call to readIni().
        $this->language->readIni();

        $defaultTicketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $projects = $defaultTicketTemplateRepository->getAllAvailableProjects();

        $templates = $defaultTicketTemplateRepository->getAllAvailableTemplates();

        $this->tpl->assign('projects', $projects);
        $this->tpl->assign('templates', $templates);
        $this->tpl->assign('noDefaultTranslationKey', self::NO_DEFAULT_TRANSLATION_KEY);

        return $this->tpl->display('ticketTemplate.settings');
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
        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $projects = $ticketTemplateRepository->getAllAvailableProjects();

        // We should receive a param per project.
        // Also note that the default 'max_input_vars' is 1000,
        // hence this will fail if more than 1000 projects exists.
        if (count($projects) != count($params)) {
            $this->tpl->setNotification($this->language->__('tickettemplate.settings.failed_message'), 'error');
        } else {
            // Do the updating if change detected.
            foreach ($projects as $project) {
                $projectId = $project['projectId'];
                $compareValue = $params[$projectId] === self::NO_DEFAULT_TRANSLATION_KEY ? null : $params[$projectId];
                if ($project['templateId'] != $compareValue) {
                    $ticketTemplateRepository->handleTemplateProjectRelation($compareValue, $projectId);
                }
            }

            $this->tpl->setNotification($this->language->__('tickettemplate.settings.success_message'), 'success');
        }

        return Frontcontroller::redirect(BASE_URL . '/TicketTemplate/settings');
    }
}

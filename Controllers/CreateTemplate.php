<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
use Leantime\Plugins\DefaultTicketTemplate\Repository\DefaultTicketTemplateRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create template Controller for DefaultTicketTemplate plugin
 *
 * @package    leantime
 * @subpackage plugins
 */
class CreateTemplate extends Controller
{
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

        return $this->tpl->display('defaultTicketTemplate.createTemplate');
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
        if (isset($params['title']) && isset($params['content'])) {
            $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
            $defaultTicketTemplateRepository->addTemplate($params['title'], $params['content']);

            $this->tpl->setNotification(__('tickettemplate.create.success_message'), 'success');
        } else {
            $this->tpl->setNotification(__('tickettemplate.create.failed_message'), 'error');
        }

        return Frontcontroller::redirect(BASE_URL . '/DefaultTicketTemplate/listTemplates');
    }
}

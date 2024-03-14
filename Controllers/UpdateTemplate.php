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
class UpdateTemplate extends Controller
{
    /**
     * Get method.
     *
     * @param array $params
     *
     * @return Response
     */
    public function get(array $params): Response
    {
        // Currently, translations are read before the plugin register is handled,
        // resulting in plugin translations not being considered,
        // without an extra call to readIni().
        $this->language->readIni();

        if (!isset($params['id'])) {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }

        $id = $params['id'];

        $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
        $templates = $defaultTicketTemplateRepository->getTemplateById($id);

        if (count($templates) !== 1) {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }

        $template = reset($templates);

        $this->tpl->assign('template', $template);

        return $this->tpl->display('defaultTicketTemplate.updateTemplate');
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
        if (isset($params['title']) && isset($params['content']) && isset($params['id'])) {
            $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
            $defaultTicketTemplateRepository->updateTemplate($params['id'], $params['title'], $params['content']);

            $this->tpl->setNotification($this->language->__('tickettemplate.update.success_message'), 'success');
        } else {
            $this->tpl->setNotification($this->language->__('tickettemplate.update.failed_message'), 'error');
        }

        return Frontcontroller::redirect(BASE_URL . '/DefaultTicketTemplate/listTemplates');
    }
}

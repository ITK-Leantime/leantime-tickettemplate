<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\TicketTemplate\Repository\TicketTemplateRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create template Controller for TicketTemplate plugin
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
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        return $this->tpl->display('TicketTemplate.createTemplate');
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
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        if (isset($params['title']) && isset($params['content'])) {
            $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
            $ticketTemplateRepository->addTemplate($params['title'], $params['content']);

            $this->tpl->setNotification($this->language->__('tickettemplate.create.success_message'), 'success');
        } else {
            $this->tpl->setNotification($this->language->__('tickettemplate.create.failed_message'), 'error');
        }

        return Frontcontroller::redirect(BASE_URL . '/TicketTemplate/listTemplates');
    }
}

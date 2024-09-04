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
 * Create template Controller for TicketTemplate plugin
 *
 * @package    leantime
 * @subpackage plugins
 */
class UpdateTemplate extends Controller
{
    /**
     * Get method.
     *
     * @param array<string, mixed> $params
     *
     * @return Response
     */
    public function get(array $params): Response
    {
        if (!isset($params['id'])) {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }

        $id = $params['id'];

        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $templates = $ticketTemplateRepository->getTemplateById($id);

        if (count($templates) !== 1) {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }

        $template = reset($templates);

        $this->tpl->assign('template', $template);

        return $this->tpl->display('ticketTemplate.updateTemplate');
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

        if (isset($params['title']) && isset($params['content']) && isset($params['id'])) {
            $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
            $ticketTemplateRepository->updateTemplate($params['id'], $params['title'], $params['content']);

            $this->tpl->setNotification('Successfully updated template', 'success');
        } else {
            $this->tpl->setNotification('Failed updating template', 'error');
        }

        return Frontcontroller::redirect(BASE_URL . '/TicketTemplate/listTemplates');
    }
}

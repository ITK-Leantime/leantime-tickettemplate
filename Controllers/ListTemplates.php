<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\TicketTemplate\Repository\TicketTemplateRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * List templates Controller for TicketTemplate plugin
 *
 * @package    leantime
 * @subpackage plugins
 */
class ListTemplates extends Controller
{
    /**
     * Get method.
     *
     * @return Response
     */
    public function get(): Response
    {
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $templates = $ticketTemplateRepository->getAllAvailableTemplates();

        $this->tpl->assign('templates', $templates);

        return $this->tpl->display('ticketTemplate.listTemplates');
    }
}

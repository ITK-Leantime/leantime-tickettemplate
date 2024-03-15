<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller;
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
        // Currently, translations are read before the plugin register is handled,
        // resulting in plugin translations not being considered,
        // without an extra call to readIni().
        $this->language->readIni();

        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);
        $templates = $ticketTemplateRepository->getAllAvailableTemplates();

        $this->tpl->assign('templates', $templates);

        return $this->tpl->display('ticketTemplate.listTemplates');
    }
}

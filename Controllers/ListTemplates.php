<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Plugins\DefaultTicketTemplate\Repository\DefaultTicketTemplateRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * List templates Controller for DefaultTicketTemplate plugin
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

        $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);
        $templates = $defaultTicketTemplateRepository->getAllAvailableTemplates();

        $this->tpl->assign('templates', $templates);

        return $this->tpl->display('defaultTicketTemplate.listTemplates');
    }
}

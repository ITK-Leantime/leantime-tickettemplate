<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Controllers;

use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\DefaultTicketTemplate\Repository\DefaultTicketTemplateRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create template Controller for DefaultTicketTemplate plugin
 *
 * @package    leantime
 * @subpackage plugins
 */
class DeleteTemplate extends Controller
{
    /**
     * run - delete template.
     *
     * @return Response
     */
    public function run(): Response
    {
        // Currently, translations are read before the plugin register is handled,
        // resulting in plugin translations not being considered,
        // without an extra call to readIni().
        $this->language->readIni();

        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        $defaultTicketTemplateRepository = app()->make(DefaultTicketTemplateRepository::class);

        if (isset($_GET['id']) === true) {
            $id = (int) ($_GET['id']);

            if (isset($_POST['del']) === true) {
                $defaultTicketTemplateRepository->deleteTemplate($id);

                $this->tpl->setNotification($this->language->__('tickettemplate.delete.success_message'), "success");

                return Frontcontroller::redirect(BASE_URL . "/DefaultTicketTemplate/listTemplates");
            }

            //Assign template.
            $template = $defaultTicketTemplateRepository->getTemplateById($id);

            if ($template === false) {
                return $this->tpl->display('errors.error403', responseCode: 403);
            }

            $this->tpl->assign('template', $template);

            return $this->tpl->display('defaultTicketTemplate.deleteTemplate');
        } else {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }
    }
}

<?php

namespace Leantime\Plugins\TicketTemplate\Controllers;

use Leantime\Core\Controller\Controller;
use Leantime\Core\Controller\Frontcontroller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\TicketTemplate\Repository\TicketTemplateRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create template Controller for TicketTemplate plugin
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
        Auth::authOrRedirect([Roles::$owner, Roles::$admin], true);

        $ticketTemplateRepository = app()->make(TicketTemplateRepository::class);

        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];

            if (isset($_POST['del']) === true) {
                $ticketTemplateRepository->deleteTemplate($id);

                $this->tpl->setNotification('Template deleted successfully', "success");

                return Frontcontroller::redirect(BASE_URL . "/TicketTemplate/listTemplates");
            }

            //Assign template.
            $template = $ticketTemplateRepository->getTemplateById($id);

            if (!$template) {
                return $this->tpl->display('errors.error403', responseCode: 403);
            }

            $this->tpl->assign('template', $template);

            return $this->tpl->display('ticketTemplate.deleteTemplate');
        } else {
            return $this->tpl->display('errors.error403', responseCode: 403);
        }
    }
}

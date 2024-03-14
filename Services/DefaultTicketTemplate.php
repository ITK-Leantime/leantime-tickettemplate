<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Services;

use Leantime\Domain\Tickets\Models\Tickets as TicketModel;
use Leantime\Domain\Wiki\Controllers\Templates;
use Leantime\Plugins\DefaultTicketTemplate\Repository\DefaultTicketTemplateRepository;

/**
 * Default Ticket Template service.
 */
class DefaultTicketTemplate
{
    /**
     * Constructor.
     *
     * @param DefaultTicketTemplateRepository $repository
     * @param Templates                       $templates
     */
    public function __construct(
        private readonly DefaultTicketTemplateRepository $repository,
        private readonly Templates $templates,
    ) {
    }

    /**
     * Setup relation table.
     *
     * @return void
     */
    public function install(): void
    {
        $this->repository->setupTables();
    }

    /**
     * Remove relation table.
     *
     * @return void
     */
    public function uninstall(): void
    {
        $this->repository->removeTables();
    }

    /**
     * Process new ticket by using ticket template setting.
     *
     * @param TicketModel $ticket
     *
     * @return void
     */
    public function processNewTicket(TicketModel $ticket): void
    {
        $projectId = $ticket->projectId ?? null;

        if (null != $projectId) {
            $relation = $this->repository->getRelationByProjectId($projectId);

            if (count($relation) !== 1) {
                return;
            }

            $templateId = reset($relation)['templateId'];

            $templates = $this->repository->getTemplateById($templateId);

            if (count($templates) !== 1) {
                return;
            }

            $template = reset($templates);

            $ticket->description = $template['content'];
        }
    }
}

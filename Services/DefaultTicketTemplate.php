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
        $this->repository->setupTable();
    }

    /**
     * Remove relation table.
     *
     * @return void
     */
    public function uninstall(): void
    {
        $this->repository->removeTable();
    }

    /**
     * Process new ticket by using default template setting.
     *
     * @return void
     */
    public function processNewTicket(TicketModel $ticket): void
    {
        $projectId = $ticket->projectId ?? null;
        if (null != $projectId) {
            $relation = $this->repository->getRelationByProjectId($projectId);

            if (count($relation) != 1) {
                return;
            }

            $templateTitle = reset($relation)['templateName'];

            $templates = json_decode($this->templates->get([])->getContent(), true);

            foreach ($templates as $template) {
                if (($template['title'] ?? null) === $templateTitle && isset($template['content'])) {
                    $ticket->description = $template['content'];
                }
            }
        }
    }
}

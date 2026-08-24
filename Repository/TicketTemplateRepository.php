<?php

namespace Leantime\Plugins\TicketTemplate\Repository;

use Illuminate\Database\Query\Builder;

/**
 * Ticket template repository class.
 */
class TicketTemplateRepository
{
    /**
     * Returns a query builder bound to the default database connection.
     *
     * @return Builder Returns an instance of the query builder.
     */
    private function query(): Builder
    {
        return app('db')->connection()->query();
    }

    /**
     * Setup template project relation table.
     *
     * @return void
     */
    public function setupTables(): void
    {
        $connection = app('db')->connection();

        $connection->statement(<<<SQL
            CREATE TABLE IF NOT EXISTS `zp_tickettemplate_relationtemplateproject` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `projectId` int(11) DEFAULT NULL,
                `templateId` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY zp_tickettemplate_relationtemplateproject_projectId_index (`projectId`),
                KEY zp_tickettemplate_relationtemplateproject_templateId_index (`templateId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL);

        $connection->statement(<<<SQL
            CREATE TABLE IF NOT EXISTS `zp_tickettemplate_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `content` text NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL);
    }

    /**
     * Remove template project relation table.
     *
     * @return void
     */
    public function removeTables(): void
    {
        $connection = app('db')->connection();

        $connection->statement('DROP TABLE `zp_tickettemplate_relationtemplateproject`;');
        $connection->statement('DROP TABLE `zp_tickettemplate_templates`;');
    }

    /**
     * Add template project relation.
     *
     * @param int $templateId
     * @param int $projectId
     *
     * @return void
     */
    public function addTemplateProjectRelation(int $templateId, int $projectId): void
    {
        $this->query()
            ->from('zp_tickettemplate_relationtemplateproject')
            ->insert([
                'projectId' => $projectId,
                'templateId' => $templateId,
            ]);
    }

    /**
     * Handle template project relation.
     *
     * @param ?int $templateId
     * @param int  $projectId
     *
     * @return void
     */
    public function handleTemplateProjectRelation(?int $templateId, int $projectId): void
    {
        $existingRelation = $this->getRelationByProjectId($projectId);

        if (null === $templateId) {
            // Handle removal of template project relation.
            if (!empty($existingRelation)) {
                $this->deleteTemplateProjectRelation($projectId);
            }
        } else {
            // Add/Update relation.
            if (empty($existingRelation)) {
                $this->addTemplateProjectRelation($templateId, $projectId);
            } else {
                $this->updateTemplateProjectRelation($templateId, $projectId);
            }
        }
    }

    /**
     * Update template project relation.
     *
     * @param int $templateId
     * @param int $projectId
     *
     * @return void
     */
    public function updateTemplateProjectRelation(int $templateId, int $projectId): void
    {
        $this->query()
            ->from('zp_tickettemplate_relationtemplateproject')
            ->where('projectId', '=', $projectId)
            ->update([
                'templateId' => $templateId,
            ]);
    }

    /**
     * Delete template project relation.
     *
     * @param int $projectId
     *
     * @return void
     */
    public function deleteTemplateProjectRelation(int $projectId): void
    {
        $this->query()
            ->from('zp_tickettemplate_relationtemplateproject')
            ->where('projectId', '=', $projectId)
            ->delete();
    }

    /**
     * Get template project relations by project id.
     *
     * @param int $projectId
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRelationByProjectId(int $projectId): array
    {
        return $this->query()
            ->from('zp_tickettemplate_relationtemplateproject')
            ->where('projectId', '=', $projectId)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Get all available projects and their ticket template.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllAvailableProjects(): array
    {
        return $this->query()
            ->from('zp_projects')
            ->select([
                'zp_projects.id AS projectId',
                'zp_projects.name AS projectName',
                'zp_tickettemplate_templates.id AS templateId',
            ])
            ->leftJoin(
                'zp_tickettemplate_relationtemplateproject',
                'zp_projects.id',
                '=',
                'zp_tickettemplate_relationtemplateproject.projectId'
            )
            ->leftJoin(
                'zp_tickettemplate_templates',
                'zp_tickettemplate_relationtemplateproject.templateId',
                '=',
                'zp_tickettemplate_templates.id'
            )
            ->orderBy('projectName')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Get all available templates.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllAvailableTemplates(): array
    {
        return $this->query()
            ->from('zp_tickettemplate_templates')
            ->select([
                'zp_tickettemplate_templates.id AS id',
                'zp_tickettemplate_templates.title AS title',
            ])
            ->orderBy('title')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Add template.
     *
     * @param string $title
     * @param string $content
     *
     * @return void
     */
    public function addTemplate(string $title, string $content): void
    {
        $this->query()
            ->from('zp_tickettemplate_templates')
            ->insert([
                'title' => $title,
                'content' => $content,
            ]);
    }

    /**
     * Get template by id.
     *
     * @param int $id
     *
     * @return array<int, array<string, mixed>>
     */
    public function getTemplateById(int $id): array
    {
        return $this->query()
            ->from('zp_tickettemplate_templates')
            ->where('id', '=', $id)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Update template.
     *
     * @param int    $id
     * @param string $title
     * @param string $content
     *
     * @return void
     */
    public function updateTemplate(int $id, string $title, string $content): void
    {
        $this->query()
            ->from('zp_tickettemplate_templates')
            ->where('id', '=', $id)
            ->update([
                'title' => $title,
                'content' => $content,
            ]);
    }

    /**
     * Delete template and its project relations.
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteTemplate(int $id): void
    {
        // Remove relations with template id
        $this->query()
            ->from('zp_tickettemplate_relationtemplateproject')
            ->where('templateId', '=', $id)
            ->delete();

        // Remove template
        $this->query()
            ->from('zp_tickettemplate_templates')
            ->where('id', '=', $id)
            ->delete();
    }
}

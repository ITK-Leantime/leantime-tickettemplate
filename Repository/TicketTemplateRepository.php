<?php

namespace Leantime\Plugins\TicketTemplate\Repository;

use Leantime\Core\Db\Db as DbCore;
use PDO;

/**
 * Ticket template repository class.
 */
class TicketTemplateRepository
{
    /**
     * Constructor.
     *
     * @param DbCore $db
     */
    public function __construct(
        private readonly DbCore $db
    ) {
    }

    /**
     * Setup template project relation table.
     *
     * @return void
     */
    public function setupTables(): void
    {
        $query = <<<SQL
            CREATE TABLE `zp_tickettemplate_relationtemplateproject` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `projectId` int(11) DEFAULT NULL,
                `templateId` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY zp_tickettemplate_relationtemplateproject_projectId_index (`projectId`),
                KEY zp_tickettemplate_relationtemplateproject_templateId_index (`templateId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE `zp_tickettemplate_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `content` text NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        $stmn = $this->db->database->prepare($query);
        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Remove template project relation table.
     *
     * @return void
     */
    public function removeTables(): void
    {
        $query = <<<SQL
            DROP TABLE `zp_tickettemplate_relationtemplateproject`;
            DROP TABLE `zp_tickettemplate_templates`;
        SQL;

        $stmn = $this->db->database->prepare($query);
        $stmn->execute();
        $stmn->closeCursor();
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
        $sql = <<<SQL
            INSERT INTO zp_tickettemplate_relationtemplateproject (
                projectId,
                templateId
            ) VALUES (
            	:projectId,
            	:templateId
            );
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->bindValue(':templateId', $templateId, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
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
        $sql = <<<SQL
            UPDATE zp_tickettemplate_relationtemplateproject
            SET
                templateId = :templateId
            WHERE
                projectId = :projectId;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->bindValue(':templateId', $templateId, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
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

        $sql = <<<SQL
            DELETE FROM zp_tickettemplate_relationtemplateproject
            WHERE
                projectId = :projectId;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Get template project relation by project id.
     *
     * @param int $projectId
     *
     * @return bool|array<string, mixed>
     */
    public function getRelationByProjectId(int $projectId): bool|array
    {
        // Check if project already has relation
        $sql = <<<SQL
            SELECT
                *
            FROM zp_tickettemplate_relationtemplateproject
            WHERE
                projectId = :projectId;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
    }

    /**
     * Get all available projects and their ticket template.
     *
     * @return bool|array<string, mixed>
     */
    public function getAllAvailableProjects(): bool|array
    {
        $sql = <<<SQL
            SELECT
                zp_projects.id AS projectId,
                zp_projects.name AS projectName,
                zp_tickettemplate_templates.id AS templateId
            FROM zp_projects
            LEFT JOIN
                zp_tickettemplate_relationtemplateproject ON zp_projects.id = zp_tickettemplate_relationtemplateproject.projectId
            LEFT JOIN
                zp_tickettemplate_templates ON zp_tickettemplate_relationtemplateproject.templateId = zp_tickettemplate_templates.id
            ORDER BY
                projectName;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
    }

    /**
     * Get all available templates.
     *
     * @return bool|array<string, mixed>
     */
    public function getAllAvailableTemplates(): bool|array
    {
        $sql = <<<SQL
            SELECT
                zp_tickettemplate_templates.id AS id,
                zp_tickettemplate_templates.title AS title
            FROM zp_tickettemplate_templates
            ORDER BY
                title;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
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
        $sql = <<<SQL
            INSERT INTO zp_tickettemplate_templates (
                title,
                content
            ) VALUES (
            	:title,
            	:content
            );
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':title', $title);
        $stmn->bindValue(':content', $content);

        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Get template by id.
     *
     * @param int $id
     *
     * @return bool|array<string, mixed>
     */
    public function getTemplateById(int $id): bool|array
    {
        $sql = <<<SQL
            SELECT
                *
            FROM zp_tickettemplate_templates
            WHERE
                id = :id;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':id', $id, PDO::PARAM_INT);
        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
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
        $sql = <<<SQL
            UPDATE zp_tickettemplate_templates
            SET
                title = :title,
                content = :content
            WHERE
                id = :id;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':id', $id, PDO::PARAM_INT);
        $stmn->bindValue(':title', $title);
        $stmn->bindValue(':content', $content);

        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Delete template.
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteTemplate(int $id): void
    {
        // Remove relations with template id
        $sql = <<<SQL
            DELETE FROM
                zp_tickettemplate_relationtemplateproject
            WHERE
                zp_tickettemplate_relationtemplateproject.templateId = :id;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':id', $id, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();

        // Remove template
        $sql = <<<SQL
            DELETE FROM
                zp_tickettemplate_templates
            WHERE
                zp_tickettemplate_templates.id = :id;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':id', $id, PDO::PARAM_INT);

        $stmn->execute();
        $stmn->closeCursor();
    }
}

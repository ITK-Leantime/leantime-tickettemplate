<?php

namespace Leantime\Plugins\DefaultTicketTemplate\Repository;

use Leantime\Core\Db as DbCore;
use PDO;

/**
 * Default ticket template repository class.
 */
class DefaultTicketTemplateRepository
{
    /**
     * Constructor.
     *
     * @param DbCore $db
     */
    public function __construct(private readonly DbCore $db)
    {
    }

    /**
     * Setup template project relation table.
     *
     * @return void
     */
    public function setupTable(): void
    {
        $query = <<<SQL
            CREATE TABLE `zp_relationtemplateproject` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `projectId` int(11) DEFAULT NULL,
                `templateName` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY zp_relationtemplateproject_projectId_index (`projectId`)
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
    public function removeTable(): void
    {
        $query = <<<SQL
            DROP TABLE `zp_relationtemplateproject`;
        SQL;

        $stmn = $this->db->database->prepare($query);
        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Add template project relation.
     *
     * @param string $templateName
     * @param int    $projectId
     *
     * @return void
     */
    public function addTemplateProjectRelation(string $templateName, int $projectId): void
    {
        $sql = <<<SQL
            INSERT INTO zp_relationtemplateproject (
                projectId,
                templateName
            ) VALUES (
            	:projectId,
            	:templateName
            );
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->bindValue(':templateName', $templateName);

        $stmn->execute();
        $stmn->closeCursor();
    }

    /**
     * Handle template project relation.
     *
     * @param ?string $templateName
     * @param int     $projectId
     *
     * @return void
     */
    public function handleTemplateProjectRelation(?string $templateName, int $projectId): void
    {
        $existingRelation = $this->getRelationByProjectId($projectId);

        if (null === $templateName) {
            // Handle removal of template project relation.
            if (!empty($existingRelation)) {
                $this->deleteTemplateProjectRelation($projectId);
            }
        } else {
            // Add/Update relation.
            if (empty($existingRelation)) {
                $this->addTemplateProjectRelation($templateName, $projectId);
            } else {
                $this->updateTemplateProjectRelation($templateName, $projectId);
            }
        }
    }

    /**
     * Update template project relation.
     *
     * @param string $templateName
     * @param int    $projectId
     *
     * @return void
     */
    public function updateTemplateProjectRelation(string $templateName, int $projectId): void
    {
        $sql = <<<SQL
            UPDATE zp_relationtemplateproject
            SET
                templateName = :templateName
            WHERE
                projectId = :projectId;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmn->bindValue(':templateName', $templateName);

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
            DELETE FROM zp_relationtemplateproject
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
     * @return bool|array
     */
    public function getRelationByProjectId(int $projectId): bool|array
    {
        // Check if project already has relation
        $sql = <<<SQL
            SELECT
                *
            FROM zp_relationtemplateproject
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
     * Get all available projects and their default ticket template.
     *
     * @return bool|array
     */
    public function getAllAvailableProjects(): bool|array
    {
        $sql = <<<SQL
            SELECT
                zp_projects.id AS projectId,
                zp_projects.name AS projectName,
                zp_relationtemplateproject.templateName AS templateName
            FROM zp_projects
            LEFT JOIN
                zp_relationtemplateproject ON zp_projects.id = zp_relationtemplateproject.projectId
            ORDER BY
                projectName;
        SQL;

        $stmn = $this->db->database->prepare($sql);

        $stmn->execute();
        $values = $stmn->fetchAll();
        $stmn->closeCursor();

        return $values;
    }
}

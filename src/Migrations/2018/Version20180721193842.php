<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180721193842 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE link_image CHANGE link link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_page_link_images DROP INDEX UNIQ_7DFF150CF4B98442, ADD INDEX IDX_7DFF150CF4B98442 (main_page)');
        $this->addSql('ALTER TABLE main_page_link_images DROP INDEX IDX_7DFF150C6A865881, ADD UNIQUE INDEX UNIQ_7DFF150C6A865881 (link_image)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE link_image CHANGE link link VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE main_page_link_images DROP INDEX IDX_7DFF150CF4B98442, ADD UNIQUE INDEX UNIQ_7DFF150CF4B98442 (main_page)');
        $this->addSql('ALTER TABLE main_page_link_images DROP INDEX UNIQ_7DFF150C6A865881, ADD INDEX IDX_7DFF150C6A865881 (link_image)');
    }
}

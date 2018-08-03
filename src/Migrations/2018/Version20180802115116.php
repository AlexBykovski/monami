<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802115116 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_group ADD parent_group INT DEFAULT NULL, DROP group_id');
        $this->addSql('ALTER TABLE product_group ADD CONSTRAINT FK_CC9C3F99A1F1D066 FOREIGN KEY (parent_group) REFERENCES product_group (id)');
        $this->addSql('CREATE INDEX IDX_CC9C3F99A1F1D066 ON product_group (parent_group)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_group DROP FOREIGN KEY FK_CC9C3F99A1F1D066');
        $this->addSql('DROP INDEX IDX_CC9C3F99A1F1D066 ON product_group');
        $this->addSql('ALTER TABLE product_group ADD group_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP parent_group');
    }
}

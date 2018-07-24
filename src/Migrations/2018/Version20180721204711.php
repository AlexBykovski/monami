<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180721204711 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contacts_page CHANGE contacts contacts LONGTEXT DEFAULT NULL, CHANGE requisites requisites LONGTEXT DEFAULT NULL, CHANGE map map LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE promo_code CHANGE discount discount NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contacts_page CHANGE contacts contacts LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE requisites requisites LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE map map LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE promo_code CHANGE discount discount NUMERIC(10, 0) NOT NULL');
    }
}

<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180718210300 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacancy_block (id INT AUTO_INCREMENT NOT NULL, vacancy INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_5A55E840A9346CBD (vacancy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacancy_block ADD CONSTRAINT FK_5A55E840A9346CBD FOREIGN KEY (vacancy) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE vacancy ADD title VARCHAR(255) NOT NULL, DROP salary, DROP city, DROP experience, DROP skills, DROP callback_email, DROP duties, DROP demands, DROP conditions, DROP address, DROP type_employment');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vacancy_block');
        $this->addSql('ALTER TABLE vacancy ADD city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD experience VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD skills VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD callback_email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD duties LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD demands LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD conditions LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD type_employment VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE title salary VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}

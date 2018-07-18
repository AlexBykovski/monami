<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180715195011 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE about_us_page (id INT AUTO_INCREMENT NOT NULL, short_description LONGTEXT NOT NULL, full_description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts_page (id INT AUTO_INCREMENT NOT NULL, contacts LONGTEXT NOT NULL, requisites LONGTEXT NOT NULL, map LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cooperation (id INT AUTO_INCREMENT NOT NULL, schedule LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cooperation_block (id INT AUTO_INCREMENT NOT NULL, cooperation INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_8DD450C07DD5ACEB (cooperation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discounted_item_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D22944588D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link_image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_page (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_page_link_images (main_page INT NOT NULL, link_image INT NOT NULL, UNIQUE INDEX UNIQ_7DFF150CF4B98442 (main_page), INDEX IDX_7DFF150C6A865881 (link_image), PRIMARY KEY(main_page, link_image)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacancy (id INT AUTO_INCREMENT NOT NULL, salary VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, experience VARCHAR(255) NOT NULL, skills VARCHAR(255) NOT NULL, callback_email VARCHAR(255) NOT NULL, duties LONGTEXT NOT NULL, demands LONGTEXT NOT NULL, conditions LONGTEXT NOT NULL, address VARCHAR(255) NOT NULL, type_employment VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cooperation_block ADD CONSTRAINT FK_8DD450C07DD5ACEB FOREIGN KEY (cooperation) REFERENCES cooperation (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944588D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE main_page_link_images ADD CONSTRAINT FK_7DFF150CF4B98442 FOREIGN KEY (main_page) REFERENCES main_page (id)');
        $this->addSql('ALTER TABLE main_page_link_images ADD CONSTRAINT FK_7DFF150C6A865881 FOREIGN KEY (link_image) REFERENCES link_image (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cooperation_block DROP FOREIGN KEY FK_8DD450C07DD5ACEB');
        $this->addSql('ALTER TABLE main_page_link_images DROP FOREIGN KEY FK_7DFF150C6A865881');
        $this->addSql('ALTER TABLE main_page_link_images DROP FOREIGN KEY FK_7DFF150CF4B98442');
        $this->addSql('DROP TABLE about_us_page');
        $this->addSql('DROP TABLE contacts_page');
        $this->addSql('DROP TABLE cooperation');
        $this->addSql('DROP TABLE cooperation_block');
        $this->addSql('DROP TABLE discounted_item_page');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE link_image');
        $this->addSql('DROP TABLE main_page');
        $this->addSql('DROP TABLE main_page_link_images');
        $this->addSql('DROP TABLE vacancy');
    }
}

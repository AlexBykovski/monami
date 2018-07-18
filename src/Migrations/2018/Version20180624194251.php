<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180624194251 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', phone VARCHAR(255) DEFAULT NULL, api_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, promocode_id INT DEFAULT NULL, client_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_2246507BC76C06D9 (promocode_id), UNIQUE INDEX UNIQ_2246507B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, basket_id INT DEFAULT NULL, count INT NOT NULL, INDEX IDX_17ED14B44584665A (product_id), INDEX IDX_17ED14B41BE1FB52 (basket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, manager INT DEFAULT NULL, contr_agent VARCHAR(255) DEFAULT NULL, INDEX IDX_C7440455FA2425B9 (manager), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, api_id VARCHAR(255) NOT NULL, sima_code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, cost NUMERIC(10, 2) NOT NULL, left_count INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D34A04ADFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_group (id INT AUTO_INCREMENT NOT NULL, api_id VARCHAR(255) NOT NULL, sima_code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, group_id VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_code (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, discount NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL, is_used TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, client_id INT DEFAULT NULL, count INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6117D13B4584665A (product_id), INDEX IDX_6117D13B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BC76C06D9 FOREIGN KEY (promocode_id) REFERENCES promo_code (id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B44584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B41BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455FA2425B9 FOREIGN KEY (manager) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFE54D947 FOREIGN KEY (group_id) REFERENCES product_group (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE basket_product DROP FOREIGN KEY FK_17ED14B41BE1FB52');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507B19EB6921');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B19EB6921');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455FA2425B9');
        $this->addSql('ALTER TABLE basket_product DROP FOREIGN KEY FK_17ED14B44584665A');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFE54D947');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BC76C06D9');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE basket_product');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_group');
        $this->addSql('DROP TABLE promo_code');
        $this->addSql('DROP TABLE purchase');
    }
}

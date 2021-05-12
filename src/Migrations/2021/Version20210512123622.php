<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512123622 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE oldcost oldcost NUMERIC(10, 2) NOT NULL');
        $this->addSql('update product set group_id = NULL where id IN (SELECT * FROM (select p.id from product_group as pg right join product as p on p.group_id = pg.id where pg.id IS NULL)tblTmp)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFE54D947 FOREIGN KEY (group_id) REFERENCES product_group (id)');
        $this->addSql('ALTER TABLE user CHANGE enabled enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE discount discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE vacancy_block ADD CONSTRAINT FK_5A55E840A9346CBD FOREIGN KEY (vacancy) REFERENCES vacancy (id)');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE basket_product ADD CONSTRAINT FK_17ED14B41BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id)');
        $this->addSql('ALTER TABLE main_page_link_images ADD CONSTRAINT FK_7DFF150CF4B98442 FOREIGN KEY (main_page) REFERENCES main_page (id)');
        $this->addSql('ALTER TABLE main_page_link_images ADD CONSTRAINT FK_7DFF150C6A865881 FOREIGN KEY (link_image) REFERENCES link_image (id)');
        $this->addSql('update basket set discount = 0.00 where discount IS NULL');
        $this->addSql('ALTER TABLE manager_slider CHANGE api_id api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE basket DROP promocode_id, CHANGE discount discount NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE basket ADD promocode_id INT DEFAULT NULL, CHANGE discount discount NUMERIC(10, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE basket_product DROP FOREIGN KEY FK_17ED14B44584665A');
        $this->addSql('ALTER TABLE basket_product DROP FOREIGN KEY FK_17ED14B41BE1FB52');
        $this->addSql('ALTER TABLE client CHANGE discount discount DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE main_page_link_images DROP FOREIGN KEY FK_7DFF150CF4B98442');
        $this->addSql('ALTER TABLE main_page_link_images DROP FOREIGN KEY FK_7DFF150C6A865881');
        $this->addSql('ALTER TABLE manager_slider CHANGE api_id api_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFE54D947');
        $this->addSql('ALTER TABLE product CHANGE oldcost oldcost NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE enabled enabled INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE vacancy_block DROP FOREIGN KEY FK_5A55E840A9346CBD');
    }
}

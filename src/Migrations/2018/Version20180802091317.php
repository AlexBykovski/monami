<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802091317 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO `import_detail` (`name`, `name_code`, `url`, `images_url`) VALUES 
            ("products", "products", "http://87.252.247.8:58080/Sources/Forsite/tovar.xml", "http://87.252.247.8:58080/Sources/Forsite/kartinki/"), 
            ("manager", "manager", "http://87.252.247.8:58080/Sources/Forsite/manager.xml", "http://87.252.247.8:58080/Sources/Forsite/kartinki/"),
            ("clients", "clients", "http://87.252.247.8:58080/Sources/Forsite/klient.xml", "http://87.252.247.8:58080/Sources/Forsite/kartinki/")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM `import_detail`');
    }
}

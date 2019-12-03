<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128112326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP INDEX UNIQ_F5299398F128501D, ADD INDEX IDX_F5299398F128501D (adres_id)');
        $this->addSql('ALTER TABLE `order` CHANGE adres_id adres_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP INDEX IDX_F5299398F128501D, ADD UNIQUE INDEX UNIQ_F5299398F128501D (adres_id)');
        $this->addSql('ALTER TABLE `order` CHANGE adres_id adres_id INT NOT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128103716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE productg');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939812136921');
        $this->addSql('DROP INDEX UNIQ_F529939812136921 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE delivery_id adres_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F128501D FOREIGN KEY (adres_id) REFERENCES adres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398F128501D ON `order` (adres_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE productg (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F128501D');
        $this->addSql('DROP INDEX UNIQ_F5299398F128501D ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE adres_id delivery_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939812136921 FOREIGN KEY (delivery_id) REFERENCES adres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F529939812136921 ON `order` (delivery_id)');
    }
}

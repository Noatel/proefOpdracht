<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128103138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986F4F78C5');
        $this->addSql('DROP INDEX UNIQ_F52993986F4F78C5 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE delivery_id_id delivery_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939812136921 FOREIGN KEY (delivery_id) REFERENCES adres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F529939812136921 ON `order` (delivery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939812136921');
        $this->addSql('DROP INDEX UNIQ_F529939812136921 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE delivery_id delivery_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986F4F78C5 FOREIGN KEY (delivery_id_id) REFERENCES adres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993986F4F78C5 ON `order` (delivery_id_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128102109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE productg (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('DROP INDEX IDX_D34A04ADFCDAEAAA ON product');
        $this->addSql('ALTER TABLE product DROP order_id_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986F4F78C5');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DE18E50B');
        $this->addSql('DROP INDEX UNIQ_F52993986F4F78C5 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398DE18E50B ON `order`');
        $this->addSql('ALTER TABLE `order` DROP product_id_id, DROP delivery_id_id');
        $this->addSql('ALTER TABLE order_rule ADD order_id_id INT NOT NULL, ADD product_id_id INT DEFAULT NULL, ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DBFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DBDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_B2996DBFCDAEAAA ON order_rule (order_id_id)');
        $this->addSql('CREATE INDEX IDX_B2996DBDE18E50B ON order_rule (product_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE productg');
        $this->addSql('ALTER TABLE `order` ADD product_id_id INT NOT NULL, ADD delivery_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986F4F78C5 FOREIGN KEY (delivery_id_id) REFERENCES adres (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DE18E50B FOREIGN KEY (product_id_id) REFERENCES order_rule (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993986F4F78C5 ON `order` (delivery_id_id)');
        $this->addSql('CREATE INDEX IDX_F5299398DE18E50B ON `order` (product_id_id)');
        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DBFCDAEAAA');
        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DBDE18E50B');
        $this->addSql('DROP INDEX IDX_B2996DBFCDAEAAA ON order_rule');
        $this->addSql('DROP INDEX IDX_B2996DBDE18E50B ON order_rule');
        $this->addSql('ALTER TABLE order_rule DROP order_id_id, DROP product_id_id, DROP quantity');
        $this->addSql('ALTER TABLE product ADD order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES order_rule (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADFCDAEAAA ON product (order_id_id)');
    }
}

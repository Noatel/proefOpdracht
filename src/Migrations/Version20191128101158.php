<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128101158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adres (id INT AUTO_INCREMENT NOT NULL, adres VARCHAR(255) NOT NULL, house_number VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, residence VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_D34A04ADFCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, delivery_id_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_F5299398DE18E50B (product_id_id), UNIQUE INDEX UNIQ_F52993986F4F78C5 (delivery_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_rule (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES order_rule (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DE18E50B FOREIGN KEY (product_id_id) REFERENCES order_rule (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986F4F78C5 FOREIGN KEY (delivery_id_id) REFERENCES adres (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986F4F78C5');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DE18E50B');
        $this->addSql('DROP TABLE adres');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_rule');
    }
}

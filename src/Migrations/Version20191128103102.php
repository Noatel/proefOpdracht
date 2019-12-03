<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128103102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DBDE18E50B');
        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DBFCDAEAAA');
        $this->addSql('DROP INDEX IDX_B2996DBDE18E50B ON order_rule');
        $this->addSql('DROP INDEX IDX_B2996DBFCDAEAAA ON order_rule');
        $this->addSql('ALTER TABLE order_rule ADD product_id INT NOT NULL, ADD order_id INT NOT NULL, DROP order_id_id, DROP product_id_id');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DB8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_B2996DB4584665A ON order_rule (product_id)');
        $this->addSql('CREATE INDEX IDX_B2996DB8D9F6D38 ON order_rule (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DB4584665A');
        $this->addSql('ALTER TABLE order_rule DROP FOREIGN KEY FK_B2996DB8D9F6D38');
        $this->addSql('DROP INDEX IDX_B2996DB4584665A ON order_rule');
        $this->addSql('DROP INDEX IDX_B2996DB8D9F6D38 ON order_rule');
        $this->addSql('ALTER TABLE order_rule ADD order_id_id INT NOT NULL, ADD product_id_id INT NOT NULL, DROP product_id, DROP order_id');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DBDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_rule ADD CONSTRAINT FK_B2996DBFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_B2996DBDE18E50B ON order_rule (product_id_id)');
        $this->addSql('CREATE INDEX IDX_B2996DBFCDAEAAA ON order_rule (order_id_id)');
    }
}

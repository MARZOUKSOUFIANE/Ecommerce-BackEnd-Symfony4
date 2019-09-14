<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190829020833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09BA8C9295');
        $this->addSql('CREATE TABLE ordere (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, date DATE DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_11FB48BF19EB6921 (client_id), UNIQUE INDEX UNIQ_11FB48BF4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ordere ADD CONSTRAINT FK_11FB48BF19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE ordere ADD CONSTRAINT FK_11FB48BF4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09BA8C9295');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09BA8C9295 FOREIGN KEY (ordere_id) REFERENCES ordere (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09BA8C9295');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, date DATE DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F529939819EB6921 (client_id), UNIQUE INDEX UNIQ_F52993984C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('DROP TABLE ordere');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09BA8C9295');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09BA8C9295 FOREIGN KEY (ordere_id) REFERENCES `order` (id)');
    }
}

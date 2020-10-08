<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006105402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment ADD galery_id INT NOT NULL');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBDA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id)');
        $this->addSql('CREATE INDEX IDX_795FD9BBDA40A005 ON attachment (galery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BBDA40A005');
        $this->addSql('DROP INDEX IDX_795FD9BBDA40A005 ON attachment');
        $this->addSql('ALTER TABLE attachment DROP galery_id');
    }
}

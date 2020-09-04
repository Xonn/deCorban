<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200902100603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE galery_user (galery_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4F5F4D32DA40A005 (galery_id), INDEX IDX_4F5F4D32A76ED395 (user_id), PRIMARY KEY(galery_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE galery_user ADD CONSTRAINT FK_4F5F4D32DA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galery_user ADD CONSTRAINT FK_4F5F4D32A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE galery_user');
    }
}

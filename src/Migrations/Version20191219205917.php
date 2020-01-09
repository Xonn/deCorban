<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219205917 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE galery_image (galery_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_2286070DA40A005 (galery_id), INDEX IDX_22860703DA5256D (image_id), PRIMARY KEY(galery_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE galery_image ADD CONSTRAINT FK_2286070DA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galery_image ADD CONSTRAINT FK_22860703DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galery ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE galery_image');
        $this->addSql('ALTER TABLE galery DROP updated_at');
    }
}

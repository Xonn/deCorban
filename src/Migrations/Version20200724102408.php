<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200724102408 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_galery (category_id INT NOT NULL, galery_id INT NOT NULL, INDEX IDX_4B5995B12469DE2 (category_id), INDEX IDX_4B5995BDA40A005 (galery_id), PRIMARY KEY(category_id, galery_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, galery_id INT NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_published TINYINT(1) NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CDA40A005 (galery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_comment (comment_source INT NOT NULL, comment_target INT NOT NULL, INDEX IDX_6707307C95992761 (comment_source), INDEX IDX_6707307C8C7C77EE (comment_target), PRIMARY KEY(comment_source, comment_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, thumbnail VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galery_image (galery_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_2286070DA40A005 (galery_id), INDEX IDX_22860703DA5256D (image_id), PRIMARY KEY(galery_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, age SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_galery (model_id INT NOT NULL, galery_id INT NOT NULL, INDEX IDX_28BA77BF7975B7E7 (model_id), INDEX IDX_28BA77BFDA40A005 (galery_id), PRIMARY KEY(model_id, galery_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, galery_id INT NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_16DB4F89DA40A005 (galery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, image VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_galery ADD CONSTRAINT FK_4B5995B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_galery ADD CONSTRAINT FK_4B5995BDA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id)');
        $this->addSql('ALTER TABLE comment_comment ADD CONSTRAINT FK_6707307C95992761 FOREIGN KEY (comment_source) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_comment ADD CONSTRAINT FK_6707307C8C7C77EE FOREIGN KEY (comment_target) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galery_image ADD CONSTRAINT FK_2286070DA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE galery_image ADD CONSTRAINT FK_22860703DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_galery ADD CONSTRAINT FK_28BA77BF7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_galery ADD CONSTRAINT FK_28BA77BFDA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89DA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_galery DROP FOREIGN KEY FK_4B5995B12469DE2');
        $this->addSql('ALTER TABLE comment_comment DROP FOREIGN KEY FK_6707307C95992761');
        $this->addSql('ALTER TABLE comment_comment DROP FOREIGN KEY FK_6707307C8C7C77EE');
        $this->addSql('ALTER TABLE category_galery DROP FOREIGN KEY FK_4B5995BDA40A005');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDA40A005');
        $this->addSql('ALTER TABLE galery_image DROP FOREIGN KEY FK_2286070DA40A005');
        $this->addSql('ALTER TABLE model_galery DROP FOREIGN KEY FK_28BA77BFDA40A005');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89DA40A005');
        $this->addSql('ALTER TABLE galery_image DROP FOREIGN KEY FK_22860703DA5256D');
        $this->addSql('ALTER TABLE model_galery DROP FOREIGN KEY FK_28BA77BF7975B7E7');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_galery');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_comment');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE galery');
        $this->addSql('DROP TABLE galery_image');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE model_galery');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE user');
    }
}

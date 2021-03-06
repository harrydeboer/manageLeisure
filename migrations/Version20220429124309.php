<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429124309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX name_unique (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grape (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX name_unique (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, published_at INT NOT NULL, INDEX IDX_140AB620F675F31B (author_id), UNIQUE INDEX title_unique (title), UNIQUE INDEX slug_unique (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F62F176F92F3E70 (country_id), UNIQUE INDEX name_unique (country_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subregion (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_691E795398260155 (region_id), UNIQUE INDEX name_unique (region_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taste_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, second_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_D8653117A76ED395 (user_id), UNIQUE INDEX name_unique (user_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, taste_profile_id INT DEFAULT NULL, country_id INT NOT NULL, region_id INT DEFAULT NULL, subregion_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, label_extension VARCHAR(255) NOT NULL, year INT DEFAULT NULL, rating INT NOT NULL, price INT NOT NULL, percentage INT NOT NULL, description LONGTEXT DEFAULT NULL, review LONGTEXT DEFAULT NULL, created_at INT NOT NULL, INDEX IDX_560C6468A76ED395 (user_id), INDEX IDX_560C646884ADE278 (taste_profile_id), INDEX IDX_560C6468F92F3E70 (country_id), INDEX IDX_560C646898260155 (region_id), INDEX IDX_560C6468C49AA6C (subregion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine_grape (wine_id INT NOT NULL, grape_id INT NOT NULL, INDEX IDX_573E24FD28A2BD76 (wine_id), INDEX IDX_573E24FD6B7990EA (grape_id), PRIMARY KEY(wine_id, grape_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE subregion ADD CONSTRAINT FK_691E795398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE taste_profile ADD CONSTRAINT FK_D8653117A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C6468A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C646884ADE278 FOREIGN KEY (taste_profile_id) REFERENCES taste_profile (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C6468F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C646898260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE wine ADD CONSTRAINT FK_560C6468C49AA6C FOREIGN KEY (subregion_id) REFERENCES subregion (id)');
        $this->addSql('ALTER TABLE wine_grape ADD CONSTRAINT FK_573E24FD28A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wine_grape ADD CONSTRAINT FK_573E24FD6B7990EA FOREIGN KEY (grape_id) REFERENCES grape (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176F92F3E70');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C6468F92F3E70');
        $this->addSql('ALTER TABLE wine_grape DROP FOREIGN KEY FK_573E24FD6B7990EA');
        $this->addSql('ALTER TABLE subregion DROP FOREIGN KEY FK_691E795398260155');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C646898260155');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C6468C49AA6C');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C646884ADE278');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620F675F31B');
        $this->addSql('ALTER TABLE taste_profile DROP FOREIGN KEY FK_D8653117A76ED395');
        $this->addSql('ALTER TABLE wine DROP FOREIGN KEY FK_560C6468A76ED395');
        $this->addSql('ALTER TABLE wine_grape DROP FOREIGN KEY FK_573E24FD28A2BD76');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE grape');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE subregion');
        $this->addSql('DROP TABLE taste_profile');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wine');
        $this->addSql('DROP TABLE wine_grape');
    }
}

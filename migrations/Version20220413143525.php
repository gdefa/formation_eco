<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413143525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE progress_formation');
        $this->addSql('DROP TABLE progress_lesson');
        $this->addSql('DROP TABLE progress_user');
        $this->addSql('ALTER TABLE progress ADD user_id INT DEFAULT NULL, ADD formation_id INT DEFAULT NULL, ADD lesson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_2201F246A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_2201F2465200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE progress ADD CONSTRAINT FK_2201F246CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('CREATE INDEX IDX_2201F246A76ED395 ON progress (user_id)');
        $this->addSql('CREATE INDEX IDX_2201F2465200282E ON progress (formation_id)');
        $this->addSql('CREATE INDEX IDX_2201F246CDF80196 ON progress (lesson_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE progress_formation (progress_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_B2F034725200282E (formation_id), INDEX IDX_B2F0347243DB87C9 (progress_id), PRIMARY KEY(progress_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE progress_lesson (progress_id INT NOT NULL, lesson_id INT NOT NULL, INDEX IDX_499D2524CDF80196 (lesson_id), INDEX IDX_499D252443DB87C9 (progress_id), PRIMARY KEY(progress_id, lesson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE progress_user (progress_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8D1AF980A76ED395 (user_id), INDEX IDX_8D1AF98043DB87C9 (progress_id), PRIMARY KEY(progress_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE progress_formation ADD CONSTRAINT FK_B2F034725200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress_formation ADD CONSTRAINT FK_B2F0347243DB87C9 FOREIGN KEY (progress_id) REFERENCES progress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress_lesson ADD CONSTRAINT FK_499D2524CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress_lesson ADD CONSTRAINT FK_499D252443DB87C9 FOREIGN KEY (progress_id) REFERENCES progress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress_user ADD CONSTRAINT FK_8D1AF980A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE progress_user ADD CONSTRAINT FK_8D1AF98043DB87C9 FOREIGN KEY (progress_id) REFERENCES progress (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation CHANGE title title VARCHAR(60) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE picture picture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lesson CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE video video VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE picture picture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE progress DROP FOREIGN KEY FK_2201F246A76ED395');
        $this->addSql('ALTER TABLE progress DROP FOREIGN KEY FK_2201F2465200282E');
        $this->addSql('ALTER TABLE progress DROP FOREIGN KEY FK_2201F246CDF80196');
        $this->addSql('DROP INDEX IDX_2201F246A76ED395 ON progress');
        $this->addSql('DROP INDEX IDX_2201F2465200282E ON progress');
        $this->addSql('DROP INDEX IDX_2201F246CDF80196 ON progress');
        $this->addSql('ALTER TABLE progress DROP user_id, DROP formation_id, DROP lesson_id');
        $this->addSql('ALTER TABLE quizz CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE question question VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE question2 question2 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE question3 question3 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE response_instructeur response_instructeur VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE response_instructeur2 response_instructeur2 VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE response_instructeur3 response_instructeur3 VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE section CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

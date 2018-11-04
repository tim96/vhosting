<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181104135855 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_answered TINYINT(1) DEFAULT \'0\' NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(2) NOT NULL, UNIQUE INDEX UNIQ_D4DB71B55E237E06 (name), UNIQUE INDEX UNIQ_D4DB71B577153098 (code), INDEX IDX_D4DB71B5F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, count_video INT NOT NULL, classification_number INT NOT NULL, UNIQUE INDEX UNIQ_6FBC94265E237E06 (name), INDEX IDX_6FBC9426F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, video_suggest_id INT DEFAULT NULL, language_id INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, slug VARCHAR(512) NOT NULL, description LONGTEXT DEFAULT NULL, meta LONGTEXT DEFAULT NULL, is_public TINYINT(1) DEFAULT \'0\' NOT NULL, youtube_video_id VARCHAR(255) DEFAULT NULL, duration_video VARCHAR(255) DEFAULT NULL, description_video VARCHAR(255) DEFAULT NULL, view_count INT DEFAULT 0, like_count INT DEFAULT 0, dislike_count INT DEFAULT 0, favorite_count INT DEFAULT 0, published_at DATETIME DEFAULT NULL, channel_id VARCHAR(255) DEFAULT NULL, language_code VARCHAR(255) DEFAULT NULL, comment_count INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_7CC7DA2C989D9B62 (slug), INDEX IDX_7CC7DA2CF675F31B (author_id), INDEX IDX_7CC7DA2CCB4E3C17 (video_suggest_id), INDEX IDX_7CC7DA2C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_tag (video_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_F910728729C1004E (video_id), INDEX IDX_F91072878D7B4FB4 (tags_id), PRIMARY KEY(video_id, tags_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_rate (id INT AUTO_INCREMENT NOT NULL, video_id INT DEFAULT NULL, feedback LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, rating INT NOT NULL, INDEX IDX_D87B62E529C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_suggest (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, link VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_suggest_tag (video_suggest_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_EB4514EBCB4E3C17 (video_suggest_id), INDEX IDX_EB4514EB8D7B4FB4 (tags_id), PRIMARY KEY(video_suggest_id, tags_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_583D1F3E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C560D76192FC23A8 (username_canonical), UNIQUE INDEX UNIQ_C560D761A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_C560D761C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5F675F31B FOREIGN KEY (author_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE tags ADD CONSTRAINT FK_6FBC9426F675F31B FOREIGN KEY (author_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CF675F31B FOREIGN KEY (author_id) REFERENCES fos_user_user (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CCB4E3C17 FOREIGN KEY (video_suggest_id) REFERENCES video_suggest (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F910728729C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F91072878D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_rate ADD CONSTRAINT FK_D87B62E529C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE video_suggest_tag ADD CONSTRAINT FK_EB4514EBCB4E3C17 FOREIGN KEY (video_suggest_id) REFERENCES video_suggest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_suggest_tag ADD CONSTRAINT FK_EB4514EB8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_user_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C82F1BAF4');
        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F91072878D7B4FB4');
        $this->addSql('ALTER TABLE video_suggest_tag DROP FOREIGN KEY FK_EB4514EB8D7B4FB4');
        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F910728729C1004E');
        $this->addSql('ALTER TABLE video_rate DROP FOREIGN KEY FK_D87B62E529C1004E');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CCB4E3C17');
        $this->addSql('ALTER TABLE video_suggest_tag DROP FOREIGN KEY FK_EB4514EBCB4E3C17');
        $this->addSql('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5F675F31B');
        $this->addSql('ALTER TABLE tags DROP FOREIGN KEY FK_6FBC9426F675F31B');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CF675F31B');
        $this->addSql('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447A76ED395');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_tag');
        $this->addSql('DROP TABLE video_rate');
        $this->addSql('DROP TABLE video_suggest');
        $this->addSql('DROP TABLE video_suggest_tag');
        $this->addSql('DROP TABLE fos_user_group');
        $this->addSql('DROP TABLE fos_user_user');
        $this->addSql('DROP TABLE fos_user_user_group');
    }
}

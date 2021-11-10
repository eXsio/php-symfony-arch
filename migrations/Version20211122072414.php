<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122072414 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE POST_HEADERS (id BLOB NOT NULL --(DC2Type:ulid)
        , title VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, commentsCount INTEGER NOT NULL, createdById BLOB NOT NULL --(DC2Type:ulid)
        , createdByName VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, version INTEGER NOT NULL, flatTags CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE TAGS (id BLOB NOT NULL --(DC2Type:ulid)
        , tag VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_59297992389B783 ON TAGS (tag)');
        $this->addSql('CREATE TABLE TAGS_POSTS (tagId BLOB NOT NULL --(DC2Type:ulid)
        , postId BLOB NOT NULL --(DC2Type:ulid)
        , PRIMARY KEY(tagId, postId))');
        $this->addSql('CREATE INDEX IDX_EF0A54AB6F16ADDC ON TAGS_POSTS (tagId)');
        $this->addSql('CREATE INDEX IDX_EF0A54ABE094D20D ON TAGS_POSTS (postId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE POST_HEADERS');
        $this->addSql('DROP TABLE TAGS');
        $this->addSql('DROP TABLE TAGS_POSTS');
    }

    protected function getDbName(): string
    {
        return "tags";
    }
}

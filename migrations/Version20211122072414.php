<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20211122072414 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE POST_HEADERS (id BLOB NOT NULL --(DC2Type:ulid)
        , title VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, commentsCount INTEGER NOT NULL, createdById BLOB NOT NULL --(DC2Type:ulid)
        , createdByName VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, version INTEGER NOT NULL, flatTags CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE TAGS (id BLOB NOT NULL --(DC2Type:ulid)
        , tag VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_T1 ON TAGS (tag)');
        $this->addSql('CREATE TABLE TAGS_POSTS (tagId BLOB NOT NULL --(DC2Type:ulid)
        , postId BLOB NOT NULL --(DC2Type:ulid)
        , PRIMARY KEY(tagId, postId)
        , FOREIGN KEY(tagId) REFERENCES TAGS(id)
        , FOREIGN KEY(postId) REFERENCES POST_HEADERS(id))
        
        ');
        $this->addSql('CREATE INDEX IDX_TP1 ON TAGS_POSTS (tagId)');
        $this->addSql('CREATE INDEX IDX_TP2 ON TAGS_POSTS (postId)');
        $this->addSql('CREATE INDEX IDX_TP3 ON TAGS_POSTS (tagId, postId)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE TAGS_POSTS');
        $this->addSql('DROP TABLE POST_HEADERS');
        $this->addSql('DROP TABLE TAGS');

    }

    protected function getDbName(): string
    {
        return "tags";
    }
}
